<?php

namespace App\Service;

use App\Repository\ArticleRepository;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder
{
    private $factory;
    private $repo;

    public function __construct(FactoryInterface $factory, ArticleRepository $repository)
    {
        $this->factory = $factory;
        $this->repo = $repository;
    }

    public function MyMenu(RequestStack $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'menuzord-menu menuzord-right menuzord-indented scrollable');
        $repository = $this->repo->getMenu();
        $sousmenus = $this->repo->getSousMenu();
        foreach ($repository as $key => $li) {
            $menu->addChild(
                $li['menu'],
                [
                    'route' => 'pages_view',
                    'routeParameters' => [
                        'slugMenu' => $li['slugMenu']
                    ]
                ]
            );
            foreach ($sousmenus as $sousmenu) {
                if ($li['menu'] == $sousmenu['MenuParent']) {
                    $menu[$li['menu']]->addChild(
                        $sousmenu['menu'],
                        [
                            'route' => 'pages_view',
                            'routeParameters' => ['slugMenu' => $sousmenu['slugMenu']]
                        ]
                    );
                    $menu[$li['menu']]->setChildrenAttribute('class', 'dropdown');
                }
            }
        }
        $menu->addChild('Nous joindre',  [
            'route' => 'compte_inscription',
            'routeParameters' => ['slugMenu' => 'nous-joindre']
        ]);
        //Menu nous porteur de projet
        $menu['Nous joindre']->addChild('Porteur de projet', [
            'route' => 'compte_inscription',
            'routeParameters' => ['slugMenu' => 'porteur-projet']
        ]);
        //Menu adhésion
        $menu['Nous joindre']->addChild('Devenir membre', [
            'route' => 'compte_inscription',
            'routeParameters' => ['slugMenu' => 'devenir-membre']
        ]);
        //Menu adhésion
        $menu['Nous joindre']->addChild('Devenir pertenaire', [
            'route' => 'compte_inscription',
            'routeParameters' => ['slugMenu' => 'devenir-partenaire']
        ]);
        $menu['Nous joindre']->setChildrenAttribute('class', 'dropdown');
        return $menu;
    }
}
