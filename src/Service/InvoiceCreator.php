<?php

namespace App\Service;

use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use App\Enum\InvoiceStatus;

final class InvoiceCreator
{
    public function create(Company $company, User $user, Customer $customer): Invoice
    {
        $invoice = new Invoice();
        $invoice->setCreatedAt(new \DateTimeImmutable());
        $invoice->setCustomer($customer);
        $invoice->setCompany($company);
        $invoice->setDate(new \DateTime);
        $invoice->setCreatedBy($user);
        $invoice->setIbanSnapshot($company->getIban());
        $invoice->setStatus(InvoiceStatus::PendingSending);
        return $invoice;
    }
}