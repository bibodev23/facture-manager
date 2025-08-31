<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de l\'entreprise',
            ])
            ->add('siret', null, [
                'label' => 'SIRET',
            ])
            ->add('siren', null, [
                'label' => 'SIREN',
            ])
            ->add('tvaNumber', null, [
                'label' => 'Numéro de TVA',
            ])
            ->add('address1', null, [
                'label' => 'Adresse 1',
            ])
            ->add('address2', null, [
                'label' => 'Adresse 2',
            ])
            ->add('postalCode', null, [
                'label' => 'Code postal',
            ])
            ->add('city', null, [
                'label' => 'Ville',
            ])
            ->add('phone', null, [
                'label' => 'Téléphone',
            ])
            ->add('email', null, [
                'label' => 'Email',
            ])
            ->add('iban', null, [
                'label' => 'IBAN',
            ])
            ->add('bic', null, [
                'label' => 'BIC',
            ])
            ->add('tvaRate', null, [
                'label' => 'Taux de TVA',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
            
        ]);
    }
}
