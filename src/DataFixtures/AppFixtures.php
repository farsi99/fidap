<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Projet;
use App\Entity\Role;
use App\Entity\TypeArticle;
use App\Entity\TypeProjet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        //creéons un role pour les users
        $adminRole = new Role();
        $adminRole->setTitre('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setCivilite('Monsieur')
            ->setFirstName('Farouk')
            ->setLastName('Soulé')
            ->setEmail('farouksoule@gmail.com')
            ->setMotpasse($this->encoder->encodePassword($adminUser, 'password'))
            ->setPhoto('https://avatar.io/twitter/LiiorC')
            ->addUserRole($adminRole);
        $manager->persist($adminUser);

        $users = [];
        $genres = ['male', 'female'];
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $genre = $faker->randomElement($genres);
            $photo = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(0, 99) . 'jpg';

            $hash = $this->encoder->encodePassword($user, 'password');

            if ($genre == 'male') {
                $civ = 'Monsieur';
                $picture = $photo . 'men/' . $pictureId;
            } else {
                $civ = 'Madame';
                $picture = $photo . 'women/' . $pictureId;
            }

            $user->setCivilite($civ)
                ->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setMotpasse($hash)
                ->setPhoto($picture);
            $manager->persist($user);
            $users[] = $user;
        }

        //On crée les projets
        $tabprojet = array('Agriculture', 'Economie', 'Technologie');
        $etats =     array('En attente', 'En cours', 'Validé', 'Refusé', 'Suspendue');
        for ($k = 0; $k < count($tabprojet); $k++) {
            $typepr = new  TypeProjet();
            $typepr->setLibelle($tabprojet[$k]);
            $manager->persist($typepr);
            //On crée les projet selon les types
            for ($t = 1; $t <= 40; $t++) {
                $duration = mt_rand(1, 31);
                $cretedAt = $faker->dateTime('-6 months');
                $stardDate = $faker->dateTimeBetween('- months');
                $endDate = (clone $stardDate)->modify("+$duration days");
                $projets = new Projet();
                $projets->setTitre($faker->sentence())
                    ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>')
                    ->setBudget(mt_rand(400, 20000))
                    ->setEtat($etats[mt_rand(0, 4)])
                    ->setUser($users[mt_rand(0, 9)])
                    ->setDateDebut($stardDate)
                    ->setDateFin($endDate)
                    ->setTypeProjet($typepr)
                    ->setDateCreation($cretedAt);
                $manager->persist($projets);
            }
        }

        //On crée un tableau des type
        $libelles = array('page', 'article');
        for ($i = 0; $i < count($libelles); $i++) {
            $type = new TypeArticle();
            $type->setLibelle($libelles[$i]);
            $manager->persist($type);

            //on crée les articles 
            for ($j = 0; $j < mt_rand(0, 100); $j++) {
                //creation article
                $article = new Article();
                $titre = $faker->sentence();
                $article->setTitre($titre)
                    ->setResume($faker->paragraph(5))
                    ->setContenue('<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>')
                    ->setDateCreation(new \DateTime())
                    ->setThumbnail($faker->imageUrl())
                    ->setTypeArticle($type)
                    ->setMenu($libelles[$i])
                    ->setPublication(mt_rand(0, 1))
                    ->setSlug($titre)
                    ->setSlugMenu()
                    ->setUser($users[mt_rand(0, 9)]);
                $manager->persist($article);
            }
        }
        $manager->flush();




        /*   for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article->setTitre($faker->sentence());
            $manager->persist($article);
        }*/
    }
}
