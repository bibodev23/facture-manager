<?php 

namespace App\Service;

use App\Entity\Invoice;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class EmailCreator 
{
    private $projectDir;
    private LoggerInterface $logger;

    public function __construct(ParameterBagInterface $parameterBag, LoggerInterface $logger)
    {
        $this->projectDir = $parameterBag->get('kernel.project_dir');
        $this->logger = $logger;
    }
    public function send(Invoice $invoice, MailerInterface $mailer, $pdfContent, $htmlContent): void
    {
        $email = (new Email())
            ->from(new Address('facturemanager@webmove.fr', 'Support Facture Manager'))
            ->to((string)$invoice->getCustomer()->getEmail())
            ->bcc((string)$invoice->getCompany()->getEmail())
            ->replyTo((string)$invoice->getCompany()->getEmail())
            ->subject('Votre facture n°' . $invoice->getNumber())
            ->attach($pdfContent, 'invoice.pdf', 'application/pdf')
            ->html($htmlContent);


        foreach ($invoice->getDeliveries() as $delivery) {
            $filePath = $delivery->getCmr();
            if ($filePath) {
                $absolutePath = $this->projectDir . '/var/uploads/cmrs/' . $filePath;
                if (is_file($absolutePath) && is_readable($absolutePath)) {
                    $email->attachFromPath($absolutePath, basename($absolutePath), 'application/pdf');
                } else {
                    $this->logger->error('Le fichier CMR ' . $filePath . ' est introuvable ou illisible.');
                }
            }
        }
        $mailer->send($email);
    }
}