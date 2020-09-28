<?php

namespace App\Form;

use App\Entity\Article;

use App\Form\ApplicationType;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class MenuType extends ApplicationType
{
    private $manager;
    private $repo;
    public function __construct(ObjectManager $manager, ArticleRepository $repo)
    {
        $this->manager = $manager;
        $this->repo = $repo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, $this->getConfiguration('Titre de la page correspondante', 'Titre de la page'))
            ->add('ordre', IntegerType::class, $this->getConfiguration('Ordre d\'affichage', 'Ordre affichage'))
            ->add('menu', TextType::class, $this->getConfiguration('Titre du menu', 'Labelle du menu'))
            ->add('AffichageMenu')
            ->add('publication')
            ->add('MenuParent', ChoiceType::class, [
                'choices' => $this->getParentMenu(),
                'required' => false
            ]);
    }

    //cette méhode recupere les menu pour lier avec les parent
    private function getParentMenu()
    {
        $menus = $this->repo->getMenu();
        $tabmenu = [];
        $tabmenu['Choisir'] = null;
        foreach ($menus as $menu) {
            $tabmenu[$menu['menu']] = $menu['menu'];
        }
        return $tabmenu;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
