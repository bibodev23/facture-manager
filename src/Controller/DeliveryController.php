<?php

namespace App\Controller;

use App\Entity\Delivery;
use App\Form\DeliveryType;
use App\Repository\DeliveryRepository;
use App\Service\InvoiceCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vich\UploaderBundle\Storage\StorageInterface;

#[Route('/delivery')]
final class DeliveryController extends AbstractController
{
    #[Route(name: 'app_delivery_index', methods: ['GET'])]
    public function index(DeliveryRepository $deliveryRepository): Response
    {
        /** @var App\Entity\User */
        $user = $this->getUser();
        return $this->render('delivery/index.html.twig', [
            'deliveries' => $user->getCompany()->getDeliveries(),
        ]);
    }

    #[Route('/new', name: 'app_delivery_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var App\Entity\User */
        $user = $this->getUser();
        $company = $user->getCompany();
        $delivery = new Delivery();
        $delivery->setDate(new \DateTimeImmutable());
        $delivery->setCompany($company);
        $delivery->setInvoiced(false);
        $form = $this->createForm(DeliveryType::class, $delivery);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($delivery);
            $entityManager->flush();

            $this->addFlash('success', 'La livraison a été créée avec succès.');
            return $this->redirectToRoute('app_delivery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('delivery/new.html.twig', [
            'delivery' => $delivery,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_delivery_show', methods: ['GET'])]
    public function show(Delivery $delivery): Response
    {
        return $this->render('delivery/show.html.twig', [
            'delivery' => $delivery,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_delivery_edit', methods: ['GET', 'POST'])]
    public function edit(InvoiceCalculator $invoiceCalculator, Request $request, Delivery $delivery, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeliveryType::class, $delivery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($delivery->getInvoice()) {
                $invoice = $delivery->getInvoice();
                $invoiceCalculator->calculate($invoice);
                $entityManager->persist($invoice);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_delivery_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('delivery/edit.html.twig', [
            'delivery' => $delivery,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_delivery_delete', methods: ['POST'])]
    public function delete(Request $request, Delivery $delivery, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $delivery->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($delivery);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_delivery_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delivery/{id}/cmr', name: 'delivery_cmr_download')]
    public function download(Delivery $delivery, StorageInterface $storage): Response
    {
        /** @var App\Entity\User */
        $user = $this->getUser();
        if ($delivery->getCompany() !== $user->getCompany()) {
            throw $this->createAccessDeniedException();
        }

        $path = $storage->resolvePath($delivery, 'cmrFile'); // mapping cmrs
        if (!$path || !is_file($path)) {
            throw $this->createNotFoundException('CMR introuvable');
        }

        return $this->file($path, $delivery->getCmr() ?? 'cmr.pdf');
    }
}
