<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminAcueilController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_acueil")
     */
    public function index()
    {
        return $this->render('admin/accueil/index.html.twig', [
            'controller_name' => 'AdminAcueilController',
        ]);
    }
}
