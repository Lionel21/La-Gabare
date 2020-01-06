<?php

namespace App\Form;

use App\Entity\Timetable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimetableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitDate', DateType::class, [
                'label' => 'Date',
                'format' => 'ddMMMMyyyy',
                'choice_translation_domain' => true,])
            ->add('visitTime', DateType::class, [
                'label' => 'Heure',
                'format' => 'ddMMMMyyyy',
                'choice_translation_domain' => true,])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Timetable::class,
        ]);
    }
}
