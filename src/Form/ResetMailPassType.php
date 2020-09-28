<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ResetMailPassType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('motpasse', PasswordType::class, $this->getConfiguration('Mot de passe', 'Nouveau mot de passe', ['required' => true], 'form-control'))
            ->add('confpass', PasswordType::class, $this->getConfiguration('Confirmer le mot de passe *', 'confirmer votre mot de passe', ['required' => true], 'form-control'))
            ->add('submit', SubmitType::class, array(
                'attr' => array(
                    'class' => 'btn btn-primary btn-block',
                    'label' => 'valider'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
