<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Customer;
use App\Entity\Delivery;
use App\Entity\Invoice;
use App\Entity\User;
use App\Repository\DeliveryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', null, [
                'label' => 'Numéro de facture'
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('dueDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('deliveries', EntityType::class, [
                
                'class' => Delivery::class,
                'label' => ' ',
                'choice_label' => function (Delivery $delivery) {
                    $label = $delivery->getDescription();
                    $amount = $delivery->getAmount();
                    return $label . ' - (' . number_format($amount, 2, ',', ' ') . '€)';
                },
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (DeliveryRepository $repo) use ($options) {
                    $qb = $repo->createQueryBuilder('d')
                        ->andWhere('d.customer = :customer')
                        ->setParameter('customer', $options['customer']);

                    if (!empty($options['invoice'])) {
                        $qb->andWhere('d.invoice IS NULL OR d.invoice = :invoice')
                            ->setParameter('invoice', $options['invoice']);
                    } else {
                        $qb->andWhere('d.invoice IS NULL');
                    }

                    return $qb;
                },
            ])
            ->add('notes', TextareaType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
            'customer' => null,
            'invoice' => null
        ]);
    }
}
