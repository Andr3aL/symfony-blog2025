<?php

namespace App\Form;

use App\Entity\Profil;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('firstName', TextType::class, [
                'label' => 'Votre Prénom',
                'attr' => ['placeholder' => 'EX : John'],

                'constraints' => [
                    new Length([

                        'min' => 2,
                        
                        'minMessage' => 'Votre prénom doit avoir au minimum {{ limit }} caractères',

                        'max' => 30,
                        
                        'maxMessage' => 'Votre prénom doit avoir au maximum {{ limit }} caractères',
                        
                    ])

                ]
            ])

            ->add('lastName', TextType::class, [
                'label' => 'Votre nom',
                'attr' => ['placeholder' => 'EX : DUPONT'],

                'constraints' => [
                    new Length([

                        'min' => 2,
                        
                        'minMessage' => 'Votre nom doit avoir au minimum {{ limit }} caractères',

                        'max' => 30,
                        
                        'maxMessage' => 'Votre nom doit avoir au maximum {{ limit }} caractères',
                        
                    ])

                ]
            ])

            // ->add('lastName')
            // "tu rajoutes le champ email dans le formulaire"
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'attr' => ['placeholder' => 'john.dupont@gmail.com']
            ])

            // ->add('roles')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => [
                    'label' => 'Votre mot de passe',
                    'attr' => ['placeholder' => 'John2025@'],

                    'constraints' => [
                        new Length ([

                            'min' => 8,
                            
                            'minMessage' => 'Votre mot de passe doit avoir au minimum {{ limit }} caractères',

                            'max' => 14,
                            
                            'maxMessage' => 'Votre mot de passe doit avoir au maximum {{ limit }} caractères',
                            
                        ])
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe'
                ]

            ])
            
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
            ])
            
            // ->add('profil', EntityType::class, [
            //     'class' => Profil::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
