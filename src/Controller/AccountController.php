<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\ResetMailType;
use App\Service\MailService;
use App\Form\CreateCompteType;
use App\Form\ResetMailPassType;
use App\Form\ResetPasswordType;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use App\Repository\TypeUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    private $typeuser;
    private $token;
    /**
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        return $this->render('account/login.html.twig', [
            'error' => $utils->getLastAuthenticationError(),
            'username' => $utils->getLastUsername()
        ]);
    }

    /**
     * cette méthode permet de se deconnecter
     * @Route("/logout" , name="account_logout")
     *
     * @return void
     */
    public function logout()
    { }

    /**
     * cette méthode permet de créer  la page profil
     * @Route("/compte/profil/", name="account_profil")
     * @IsGranted("ROLE_USER")
     */
    public function profil(ProjetRepository $projet)
    {

        return $this->render('account/profil.html.twig', [
            'asidemenu' => 'profil',
            'projet' => $projet->findBy([], ['id' => 'DESC'], 1)[0]
        ]);
    }

    /**
     * cette méthode traite les inscription des differents memebres
     * @Route("/compte/{slugMenu}", name="compte_inscription")
     * 
     */
    public function inscription(ObjectManager $manager, Request $request, $slugMenu, TypeUserRepository $repo, MailService $sendmail)
    {
        $user = new User();
        switch ($slugMenu) {
            case 'porteur-projet':
                $this->typeuser = 1;
                $this->token = uniqid();
                break;
            case 'devenir-membre':
                $this->typeuser = 2;
                break;
            case 'devenir-partenaire':
                $this->typeuser = 3;
        }
        $catuser = $repo->findOneBy(['id' => $this->typeuser]);
        $parm = explode('-', $slugMenu);
        $titre = ucfirst($parm[0]) . ' ' . ucfirst($parm[1]);

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($this->token)) {
                $user->setResetPassword($this->token);
            }
            $user->setTypeUser($catuser);
            $manager->persist($user);
            $manager->flush();
            if (!empty($this->typeuser == 1)) {
                $sendmail->Monmail(['email' => $user->getEmail(), 'nomComplet' => $user->getFullName(), 'token' => $this->token, 'tmp' => 'create']);
                $this->addFlash('success', 'Enregistrement effectué avec succès, Vous allez recevoir un email pour créer votre compte');
            } else {
                $this->addFlash('success', 'Enregistrement effectué avec succès, on vous contactera bientôt ! ');
            }
            return $this->redirectToRoute('compte_inscription', ['slugMenu' => $slugMenu]);
        }
        return $this->render("account/ajouter.html.twig", [
            'page' => $titre,
            'form' => $form->createView(),
            'slugMenu' => $slugMenu,
            'profil' => true
        ]);
    }

    /**
     * Cette méthode permet de créer ou initialisez son compte
     * @Route("/compte/create/{token}", name="account_create")
     */
    public function create(ObjectManager $manager, Request $request, $token, UserRepository $repo, UserPasswordEncoderInterface $encoder)
    {
        $user = $repo->findOneByResetPassword($token);
        $form = $this->createForm(CreateCompteType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getMotpasse());
            $user->setMotpasse($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'La création de votre compte est bien effectuée, merci de vous connecter !');
            return $this->redirectToRoute('account_login');
        }
        return $this->render("account/create.html.twig", [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * Cette méthode traite l'oublie d'un mot de passe en envoyant un mail pour changer
     * @Route("compte/forgot/password", name="account_forgot_passwword")
     */
    public function forgotPassword(ObjectManager $em, Request $request, UserRepository $repo, MailService $sendmail)
    {
        $user = new User();
        $form = $this->createForm(ResetMailType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $token = uniqid();
            $user = $repo->findOneByEmail($request->request->get('reset_mail')['email']);
            if (!empty($user)) {
                $user->setResetPassword($token);
                $em->persist($user);
                $em->flush();
                //Configuration de l'email
                $send = $sendmail->Monmail(['email' => $user->getEmail(), 'nomComplet' => $user->getFullName(), 'token' => $token, 'tmp' => 'reset']);
                if ($send) {
                    $this->addFlash('success', 'Vous allez recevoir un mail pour changer votre mot de passe');
                    return $this->redirectToRoute('account_login');
                } else {
                    $this->addFlash('warning', 'Une erreur s\'est produit lors de l\'envoi de votre email');
                    return $this->redirectToRoute('account_login');
                }
            } else {
                $this->addFlash('danger', 'Cet email n\'existe pas chez nous');
            }
        }
        return $this->render('account/resetmail.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * cette méthode permet de faire le changement de mot de passe via un token envoyé par mail
     * @Route("compte/forgot/{token}/password",name="account_forgot_password")
     */
    public function resetMailPass(ObjectManager $em, $token, UserRepository $repo, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $repo->findOneByResetPassword($token);
        if (!empty($user)) {
            $form = $this->createForm(ResetMailPassType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $password = $encoder->encodePassword($user, $form->getData()->getMotpasse());
                $user->setMotpasse($password);
                $user->setResetPassword('');
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Votre mot de passe a bien été modifié, merci de vous connectez');
                return $this->redirectToRoute('account_login');
            }
            return $this->render("account/resetmailpass.html.twig", [
                'form' => $form->createView()
            ]);
        } else {
            $this->addFlash('error', 'votre demandes est déjà executé!');
            return $this->redirectToRoute('account_login');
        }
    }



    /**
     * cette méthode traite la modification et complement de profil
     * @Route("/compte/editer/{id}/profil", name="compt_editer_profil")
     * @IsGranted("ROLE_USER")
     */
    public function updatePoerteur(User $user, ObjectManager $em, Request $req)
    {
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", "La modification de votre profil est effectuée avec succès !");
        }
        return $this->render("account/updateProfil.html.twig", [
            'form' => $form->createView(),
            'user' => $user,
            'asidemenu' => 'compte'
        ]);
    }


    /**
     * Cette méthode permet de faire la modification de son mot de passe
     * @Route("/compte/reset/{id}/password", name="account_reset_password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $em)
    {
        $user = $this->getUser();
        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $request->request->get('reset_password')['oldPassword'];
            // Si l'ancien mot de passe est bon
            if (!password_verify($oldPassword, $user->getMotpasse())) {
                $form->addError(new FormError('Ancien mot de passe incorrect'));
            } else {
                $user->setMotpasse($encoder->encodePassword($user, $user->getMotpasse()));
                $em->persist($user);
                $em->flush();
                $form->addError(new FormError('Votre mot de passe est modifié'));
                //return $this->redirectToRoute('account_profil');

            }
        }
        return $this->render(
            "account/resetPassword.html.twig",
            [
                'form' => $form->createView(),
                'asidemenu' => 'identifiant'
            ]
        );
    }
}
