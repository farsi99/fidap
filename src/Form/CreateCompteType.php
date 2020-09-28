<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateCompteType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, $this->getConfiguration('Email *', 'votre email', [], 'form-control'))
            ->add('motpasse', PasswordType::class, $this->getConfiguration('Mot de passe *', 'Votre mot de passe', [], 'form-control'))
            ->add('confpass', PasswordType::class, $this->getConfiguration('Confirmer le mot de passe *', 'confirmer le mot de passe', [], 'form-control'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
