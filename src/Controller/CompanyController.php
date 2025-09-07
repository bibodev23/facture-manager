<?php

namespace App\Controller;

use App\Entity\Company;
use App\Enum\LegalForm;
use App\Enum\ThemeSelection;
use App\Form\CompanyInvoiceThemeType;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_VERIFIED')]
#[Route('/company')]
final class CompanyController extends AbstractController
{
    // #[Route(name: 'app_company_index', methods: ['GET'])]
    // public function index(CompanyRepository $companyRepository): Response
    // {
    //     return $this->render('company/index.html.twig', [
    //         'companies' => $companyRepository->findAll(),
    //     ]);
    // }

    // #[Route('/new', name: 'app_company_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //    /** @var \App\Entity\User|null $user */
    //     $user = $this->getUser();
    //     $company = new Company();
    //     $form = $this->createForm(CompanyType::class, $company);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $company->setUser($user);
    //         $user->setCompany($company);
    //         $entityManager->persist($company);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('company/new.html.twig', [
    //         'company' => $company,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_company_show', methods: ['GET'])]
    // public function show(Company $company): Response
    // {
    //     return $this->render('company/show.html.twig', [
    //         'company' => $company,
    //     ]);
    // }

    #[Route('/{id}/edit', name: 'app_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        /** @var \App\Entity\User */
        $user = $this->getUser();
        $company = $user->getCompany();

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        $formTheme = $this->createForm(CompanyInvoiceThemeType::class, $company);
        $formTheme->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager->flush();

            if (count($company->getCustomers()) < 1) {
                $this->addFlash('success', 'Pensez à ajouter vos premiers clients !');
                return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('success', 'Votre entreprise a bien été modifiée !');


            return $this->redirectToRoute('app_company_edit', ['id' => $company->getId()], Response::HTTP_SEE_OTHER);
        }

                if ($formTheme->isSubmitted() && $formTheme->isValid()) {
            dd($formTheme->getData());
            $entityManager->flush();
            return $this->redirectToRoute('app_company_theme', ['id' => $company->getId()], Response::HTTP_SEE_OTHER);
        }

        
        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'form' => $form,
            'formTheme' => $formTheme
        ]);
    }

    #[Route('/{id}/theme', name: 'app_company_theme', methods: ['GET', 'POST'])]
    public function editTheme(Request $request, Company $company, EntityManagerInterface $entityManager, InvoiceRepository $invoiceRepository): Response
    {
        /** @var \App\Entity\User */
        $user = $this->getUser();
        $company = $user->getCompany();
        $invoices = $company->getInvoices();
        $firstInvoice = $invoices[0];
        $themeDefault = true;
        if ($company->getThemeSelection() === ThemeSelection::AlternativeTheme) {
            $themeDefault = false;
        }
        $formTheme = $this->createForm(CompanyInvoiceThemeType::class, $company);
        $formTheme->handleRequest($request);

        if ($formTheme->isSubmitted() && $formTheme->isValid()) {
            $entityManager->persist($company);
            $entityManager->flush();
            $this->addFlash('success', 'Votre theme a bien été modifiée !');
            return $this->redirectToRoute('app_company_theme', ['id' => $company->getId()], Response::HTTP_SEE_OTHER);
        }

        
        return $this->render('company/edit_theme.html.twig', [
            'company' => $company,
            'formTheme' => $formTheme->createView(),
            'invoice' => $firstInvoice,
            'themeDefault' => $themeDefault
        ]);
    }

    #[Route('/{id}', name: 'app_company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($company);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_company_index', [], Response::HTTP_SEE_OTHER);
    }
}
