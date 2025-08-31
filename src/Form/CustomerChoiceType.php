<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Delivery;
use App\Entity\Invoice;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\DeliveryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerChoiceType extends AbstractType
{
        public function __construct(
         private CustomerRepository $customerRepository
    ) {}
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $company = $options['company'];

        $builder->add('customer', EntityType::class, [
                'class' => Customer::class,
                'label'=>'Client',
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez un client',
                'query_builder' => function (CustomerRepository $er) use ($company) {
                $qb = $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                if ($company) {
                    $qb->andWhere('c.company = :co')->setParameter('co', $company);
                } else {
                    $qb->andWhere('1 = 0'); // sécurité si pas de company
                }
                return $qb;
            },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
            'company' => null
        ]);
    }
}