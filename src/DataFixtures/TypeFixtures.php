<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Type;
use App\Entity\Aliment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class TypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $t1= new Type();
        $t1->setLibelle("Fruits")
            ->setImage("fruits.jpg");
        $t1->setCreatedAt(new \DateTime('now'));
        $manager->persist($t1);

        $t2= new Type();
        $t2->setLibelle("Legumes")
            ->setImage("legumes.jpg");
        $t2->setCreatedAt(new \DateTime('now'));
        $manager->persist($t2);


        $alimentRepository = $manager->getRepository(Aliment::class);


        $a1 = $alimentRepository->findOneBy(["nom" => "Carotte"]);
        $a1->setType($t2);
        $manager->persist($a1);

         $a2 = $alimentRepository->findOneBy(["nom" => "Patate"]);
        $a2->setType($t2);
        $manager->persist($a2);

         $a3 = $alimentRepository->findOneBy(["nom" => "Tomate"]);
        $a3->setType($t2);
        $manager->persist($a3);

         $a4 = $alimentRepository->findOneBy(["nom" => "Pomme"]);
        $a4->setType($t1);
        $manager->persist($a4);



        $manager->flush();
    }
}
