<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyInvoiceThemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('invoicePrimaryColor', ColorType::class, [
                'label' => 'Couleur primaire',
                'required' => false

            ])
            ->add('invoiceTextColor', ColorType::class, [
                'label' => 'Couleur du texte',
                'required' => false
            ])
            ->add('logoFile', FileType::class, [
                'label' => 'Logo',
                'required' => false
            ])
        ;
    }
}