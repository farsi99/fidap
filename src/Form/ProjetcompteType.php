<?php

namespace App\Form;

use App\Entity\Projet;
use App\Entity\TypeProjet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjetcompteType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, $this->getConfiguration('Titre', 'Titre du projet', ['required' => true], 'form-control'))
            ->add('description', TextareaType::class, $this->getConfiguration('Description', 'description du projet', ['required' => false], 'form-control'))
            ->add('dateDebut', DateType::class, $this->getConfiguration('Date Début', 'date debut du projet', ['widget' => 'single_text'], 'form-control'))
            ->add('dateFin', DateType::class, $this->getConfiguration('Date Fin', 'date fin du projet', ['widget' => 'single_text'], 'form-control'))
            ->add('budget', MoneyType::class, $this->getConfiguration('Budget', 'montant du projet', [], 'form-control'))
            ->add('typeProjet', EntityType::class, $this->getConfiguration('Type de projet', 'Vuillez choisir', ['class' => TypeProjet::class, 'choice_label' => 'libelle'], 'form-control'))
            ->add('projetfichiers', FileType::class, $this->getConfiguration('Charger vos fichiers', 'charger les fichiers', [
                'required' => false,
                'multiple' => true
            ], 'form-control'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
