<?php

namespace App\Form;

use App\Entity\DrivingSchool;
use App\Entity\Invoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $drivingSchool = $options['drivingSchool'];
        $builder
            ->add('statut', ChoiceType::class, [
                'attr' => [
                    'placeholder' => 'Choississez statut'
                ],
                'choices' => [
                    'En attente' => 'En attente',
                    'Payé' => 'Payé',
                    'Annulé' => 'Annulé',
                ],
                'multiple' => false,
            ]);;
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
