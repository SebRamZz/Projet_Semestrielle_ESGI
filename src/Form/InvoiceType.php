<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\DrivingSchool;
use App\Entity\Invoice;
use App\Entity\Product;
use App\Repository\ClientRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $drivingSchool = $options['drivingSchool'];
        $builder
            ->add('typePayment', ChoiceType::class, [
                'attr' => [
                    'placeholder' => 'Choisissez le type de payment'
                ],
                'choices' => [
                    'Carte' => 'Cart',
                    'Chèque' => 'Cheque',
                    'Espèce' => 'Cash',
                ],
                'multiple' => false,
            ])
            ->add('client', EntityType::class, [
                    'class' => Client::class,
                    'query_builder' => function (ClientRepository $cr) use ($drivingSchool): QueryBuilder {
                        return $cr->queryFindByDrivingSchool($drivingSchool);
                }]
            )
            ->add('product', EntityType::class, [
                'mapped' => false,
                'class' => Product::class,
                'query_builder' => function (ProductRepository $cr) use ($drivingSchool): QueryBuilder {
                    return $cr->queryFindByDrivingSchool($drivingSchool);
                }]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('drivingSchool');
        $resolver->setAllowedTypes('drivingSchool', DrivingSchool::class);
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
