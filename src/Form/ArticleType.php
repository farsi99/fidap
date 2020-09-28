<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\TypeArticle;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, $this->getConfiguration('Titre', 'Titre de la page'))
            ->add('resume', TextType::class, $this->getConfiguration('Resumé', 'Resume de la page', ['required' => false]))
            ->add('contenue', TextareaType::class)
            ->add('dateCreation', DateTimeType::class, $this->getConfiguration('Date création', 'date de creation', ['widget' => 'single_text']))
            ->add('meta_title', TextType::class, $this->getConfiguration('Meta title', 'titre pour le refenrencement (SEO)'))
            ->add('meta_description', TextType::class, $this->getConfiguration('Meta Description', 'Meta description pour le referencement (SEO)'))
            ->add('menu', TextType::class, $this->getConfiguration('Libellé menu de la page', 'Ajouter le menu', ['required' => false]))
            ->add('AffichageMenu')
            ->add('imageFile', VichImageType::class, $this->getConfiguration('Image à la une', 'charger une image', ['required' => false]))
            ->add('TypeArticle', EntityType::class, [
                'class' => TypeArticle::class,
                'choice_label' => 'libelle'
            ])
            ->add('publication');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
