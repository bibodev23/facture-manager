<?php

namespace App\Enum;
/**
 * Enum class representing the status of a invoice.
 */
use Symfony\Component\Form\FormTypeInterfac;

enum InvoiceStatus: string
{
    case Paid = 'paid';
    case Sent = 'sent';
    case Overdue = 'overdue';
    case PendingSending = 'pending_sending';
    
    public function getLabel(): string
    {
        return match ($this) {
            self::Paid => 'Payée',
            self::Sent => 'Envoyée',
            self::Overdue => "En retard de paiement",
            self::PendingSending => "En attente d'envoi",
        };
    }
}