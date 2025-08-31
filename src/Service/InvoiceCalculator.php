<?php 

namespace App\Service;

use App\Entity\Invoice;

final class InvoiceCalculator {
    public function calculate(Invoice $invoice): void
    {
        $company = $invoice->getCompany();
        $tvaRate = $company->getTvaRate();
        $totalHT = 0;
        foreach ($invoice->getDeliveries() as $delivery) {
            $delivery->setInvoice($invoice);
            $delivery->setInvoiced(true);
            $totalHT += $delivery->getAmount();
        }
        $totalTVA = $totalHT * ($tvaRate / 100);
        $totalTTC = $totalHT + $totalTVA;
        $invoice->setTvaRate($tvaRate);
        $invoice->setTotalHT($totalHT);
        $invoice->setTotalTVA($totalTVA);
        $invoice->setTotalTTC($totalTTC);
    }
}