<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use App\Enum\InvoiceStatus;
use App\Form\CustomerChoiceType;
use App\Form\InvoiceType;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use App\Service\EmailCreator;
use App\Service\InvoiceCalculator;
use App\Service\InvoiceCreator;
use App\Service\PdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/invoice')]
final class InvoiceController extends AbstractController
{
    #[Route(name: 'app_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository): Response
    {
        /** @var App\Entity\User $user */
        $user = $this->getUser();
        $company = $user->getCompany();

        $invoices = $invoiceRepository->findByCompanyWithRelations($company);
        $invoicesOverdue = $invoiceRepository->findByCompanyWithRelations($company, InvoiceStatus::Overdue->value);
        $invoicesPendingSending = $invoiceRepository->findByCompanyWithRelations($company, InvoiceStatus::PendingSending->value);

        if (count($company->getDeliveries()) < 1) {
            $this->addFlash('info', 'Vous devez d\'abord ajouter une livraison avant de créer une facture.');
            return $this->redirectToRoute('app_delivery_new');
        }
        return $this->render('invoice/index.html.twig', [
            'invoices' => $invoices,
            'invoicesOverdue' => $invoicesOverdue,
            'invoicesPendingSending' => $invoicesPendingSending,
        ]);
    }

    #[Route('/new', name: 'app_invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $company = $user->getCompany();
        $customers = $company->getCustomers();
        $form = $this->createForm(CustomerChoiceType::class,null, [
            'company' => $company
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        /** @var Customer $customer */
        $customer = $form->get('customer')->getData();
        if ($company !== $customer->getCompany()) {
            $this->addFlash('error', 'Veuillez choisir un client de votre entreprise');
        }

            return $this->redirectToRoute('app_invoice_new_from_customer', [
                'id' => $customer->getId(),
            ]);
        }
        return $this->render('invoice/new.html.twig', [
            'form' => $form,
            'customers' => $customers
        ]);
    }

    #[Route('/new/{id}', name: 'app_invoice_new_from_customer', methods: ['GET', 'POST'])]
    public function newFromCustomer(InvoiceCreator $invoiceCreator, InvoiceCalculator $invoiceCalculator, Customer $customer, Request $request, EntityManagerInterface $entityManager, CustomerRepository $customerRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $company = $user->getCompany();
        $invoice = $invoiceCreator->create($company, $user, $customer);
        if ($company == $customer->getCompany()) {
            $form = $this->createForm(InvoiceType::class, $invoice, [
                'customer' => $customer
            ]);
        $form->handleRequest($request);
        }


        if ($form->isSubmitted() && $form->isValid()) {
            if ($company !== $customer->getCompany()) {
                $this->addFlash('error', 'Veuillez choisir un client de votre entreprise');
            }
            
            if ($form->get('deliveries')->getData()->isEmpty()) {
                $this->addFlash('error', 'Veuillez choisir au moins une livraison');
                return $this->render('invoice/new_from_customer.html.twig', [
                    'invoice' => $invoice,
                    'form' => $form,
                    'company' => $company,
                    'customer' => $customer
                ]);
            }
            $invoiceCalculator->calculate($invoice);
           
            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('app_invoice_show', [
                'id' => $invoice->getId(),
            ]);
        }

        return $this->render('invoice/new_from_customer.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
            'company' => $company,
            'customer' => $customer
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(InvoiceCalculator $invoiceCalculator, Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $customer = $invoice->getCustomer();
        $company = $customer->getCompany();
        
        if ($company !== $user->getCompany()) {
            $this->addFlash('error', 'Veuillez choisir un client de votre entreprise');
            return $this->redirectToRoute('app_invoice_index');
        }
        $form = $this->createForm(InvoiceType::class, $invoice, [
            'customer' => $customer,
            'invoice' => $invoice
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $invoiceCalculator->calculate($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('app_invoice_show', [
                'id' => $invoice->getId(),
            ]);
        }

        return $this->render('invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}', name: 'app_invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoice->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_invoice_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/pdf/{id}', name: 'app_invoice_show_pdf', methods: ['GET'])]
    public function showPdf(Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        return $this->render('invoice/pdf.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/download', name: 'app_invoice_download', methods: ['GET'])]
    public function download(Invoice $invoice, PdfGenerator $pdfGenerator): Response
    {
        $htmlContent = $this->renderView('invoice/pdf.html.twig', [
            'invoice' => $invoice,
        ]);

        $pdfContent = $pdfGenerator->generatePdf($htmlContent);

        return new Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="invoice.pdf"',
        ]);
    }

    #[Route('invoice/{id}/mail', name: 'app_invoice_mail', methods: ['GET'])]
    public function sendInvoice(Invoice $invoice, MailerInterface $mailer, Request $request, PdfGenerator $pdfGenerator, EmailCreator $emailCreator): Response
    {
        $htmlContent = $this->renderView('invoice/email.html.twig', [
            'invoice' => $invoice,
        ]);

        $invoicePdfContent = $this->renderView('invoice/pdf.html.twig', [
            'invoice' => $invoice,
        ]);

        $pdfContent = $pdfGenerator->generatePdf($invoicePdfContent);
        $emailCreator = $emailCreator->send($invoice, $mailer, $pdfContent, $htmlContent);
        $this->addFlash('success', 'La facture a bien été envoyée par email à ' . $invoice->getCustomer()->getEmail() . ' !');
        return $this->render('invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }
}