<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\TypeProjet;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $etat = [
            'En attente' => 'En attente',
            'En cours' => 'En cours',
            'Validé' => 'Validé',
            'Refusé' => 'Refusé',
            'Suspendue' => 'Suspendue'
        ];
        $builder
            ->add('titre', TextType::class, $this->getConfiguration('Titre', 'Titre du projet'))
            ->add('description', TextareaType::class, $this->getConfiguration('Description', 'Description du projet'))
            ->add('dateDebut', DateTimeType::class, $this->getConfiguration('Date début', 'date du début', ['widget' => 'single_text']))
            ->add('dateFin', DateTimeType::class, $this->getConfiguration('Date fin', 'date de fin', ['widget' => 'single_text']))
            ->add('budget')
            ->add(
                'etat',
                ChoiceType::class,
                ['choices' => $etat]
            )
            ->add('typeProjet', EntityType::class, [
                'class' => TypeProjet::class,
                'choice_label' => 'libelle'
            ])
            ->add('projetfichiers', FileType::class, $this->getConfiguration('Charger vos fichiers', 'charger les fichiers', [
                'required' => false,
                'multiple' => true
            ], 'form-control'))
            ->add('user', EntityType::class, $this->getConfiguration('Porteur de projet', 'Porteur de projet', [
                'class' => User::class,
                'choice_label' => 'NomComplet'
            ]));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
