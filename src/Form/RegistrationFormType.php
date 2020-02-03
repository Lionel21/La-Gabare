<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => new NotBlank([
                    'message' => 'Entrer une adresse mail valide'
                ]),
            ])

            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Page d\'accueil' => 'ROLE_ADMIN_HOME',
                    'Qui Sommes-Nous?' => 'ROLE_ADMIN_WHO',
                    'La Vie de La Gabare'=> 'ROLE_ADMIN_GABARE_LIFE',
                    'Nous Rejoindre' => 'ROLE_ADMIN_JOIN_US',
                    'Super Admin' => 'ROLE_SUPER_ADMIN'
                ],
                'expanded' => true,
                'multiple' => true,
            ])

            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'required'   => false,
                'mapped' => false,
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire moins de {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
