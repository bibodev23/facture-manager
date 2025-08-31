<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Customer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom'
            ])
            ->add('address1', null, [
                'label' => 'Adresse'
            ])
            ->add('address2', null, [
                'label' => 'Adresse complémentaire',
            ])
            ->add('postalCode', null, [
                'label' => 'Code Postal'
            ])
            ->add('city', null, [
                'label' => 'Ville'
            ])
            ->add('phone', null, [
                'label' => 'Téléphone'
            ])
            ->add('email', null, [
                'label' => 'Email'
            ])
            ->add('siren', null, [
                'label' => 'SIREN'
            ])
            ->add('siret', null, [
                'label' => 'SIRET'
            ])
            ->add('tva', null, [
                'label' => 'TVA'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
