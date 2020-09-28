<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civilite', ChoiceType::class, $this->getConfiguration('Civilité', 'civilité', [
                'choices' => ['Monsieur' => 'Mr', 'Madame' => 'Mme']
            ], 'form-control'))
            ->add('lastName', TextType::class, $this->getConfiguration(
                'Nom *',
                'Entrer votre nom',
                [],
                'form-control'
            ))
            ->add('firstName', TextType::class, $this->getConfiguration('Prénom *', 'Entrer votre prénom', [], 'form-control'))
            ->add('email', EmailType::class, $this->getConfiguration(
                'Email *',
                'email:exemple@gmail.com',
                [],
                'form-control'
            ))
            ->add('raisonSocial', TextType::class, $this->getConfiguration('Raison social', 'raison social', ['required' => false], 'form-control'))
            ->add('adresse', TextType::class, $this->getConfiguration('Adresse *', 'n° rue ou bd ou avenue', [], 'form-control'))
            ->add('code_postal', TextType::class, $this->getConfiguration('Code postal', 'ex: 75007', ['required' => false], 'form-control'))
            ->add('ville', TextType::class, $this->getConfiguration('Ville *', 'votre ville', [], 'form-control'))
            ->add('telephone', TextType::class, $this->getConfiguration('Télépone *', 'ex:0655983254', [], 'form-control'))
            ->add('siret', TextType::class, $this->getConfiguration(
                'Siret',
                'siret',
                ['required' => false],
                'form-control'
            ))
            ->add('lienUrl', UrlType::class, $this->getConfiguration('Site web', 'lien de votre site web', ['required' => false], 'form-control'))
            ->add('facebook', UrlType::class, $this->getConfiguration('Facebook', 'votre page facebook', ['required' => false], 'form-control'))
            ->add('twitter', UrlType::class, $this->getConfiguration('Twitter', 'votre twitter', ['required' => false], 'form-control'))
            ->add('linkdin', UrlType::class, $this->getConfiguration('Linkdin', 'votre linkdin', ['required' => false], 'form-control'))
            ->add('imageFile', VichImageType::class, $this->getConfiguration('Photo de profil', 'votre photo de profil', ['required' => false], 'form-control'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['registration']
        ]);
    }
}
