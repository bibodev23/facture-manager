<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\Delivery;
use App\Repository\CustomerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryType extends AbstractType
{
    public function __construct(
         private Security $security,
         private CustomerRepository $customerRepository
    ) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var App\Entity\User */
        $user = $this->security->getUser();
        $company = $user->getCompany();
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date de livraison',
                'widget' => 'single_text',
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'label'=>'Client',
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez un client',
                'query_builder' => function (CustomerRepository $repository) use ($company) {
                    return $repository->createQueryBuilder('c')
                        ->andWhere('c.company = :company')
                        ->setParameter('company', $company)
                        ->orderBy('c.name', 'ASC');
                },
            ])
            ->add('description')
            ->add('amount', null, [
                'label' => 'Montant HT',
            ])
            ->add('cmrFile', FileType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Delivery::class,
        ]);
    }
}
