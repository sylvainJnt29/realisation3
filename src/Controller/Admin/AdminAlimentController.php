<?php

namespace App\Controller\Admin;

use App\Entity\Aliment;
use App\Form\AlimentType;
use App\Repository\AlimentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAlimentController extends AbstractController
{
    /**
     * @Route("/admin/aliment", name="admin_aliment")
     */
    public function index(AlimentRepository $repository)
    {
        $aliments = $repository->findAll();
        return $this->render('admin/admin_aliment/adminAliment.html.twig', [
            "aliments" => $aliments
        ]);
    }

    /**
     * @Route("/admin/aliment/creation", name="admin_aliment_creation")
     * @Route("/admin/aliment/{id}", name="admin_aliment_modification", methods="GET|POST")
     */
    public function ajoutEtModif(Aliment $aliment = null,Request $request,EntityManagerInterface $entityManager)
    {
        if(!$aliment){
            $aliment = new Aliment();
        }
        $form =$this->createForm(AlimentType::class,$aliment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $modif = $aliment->getId() !== null;
            $entityManager->persist($aliment);
            $entityManager->flush();
            //$modif sera false ou true en fonction de l'identifiant, s'il existe ou non.
            //si $modif=true, on est dans la modification, sinon, c'est une création. 
            $this->addFlash("success",($modif) ? "La modification a été effectuée" : "L'ajout a été effectué");
            return $this->redirectToRoute("admin_aliment");
        }

        return $this->render('admin/admin_aliment/modificationEtAjout.html.twig', [
            "aliment" => $aliment,
            "form" => $form->createView(),
            //au moment de la création c'est élément est vide
            "isModification" => $aliment->getId() !== null
        ]);
    }

    /**
     * @Route("/admin/aliment/{id}", name="admin_aliment_suppression", methods="delete")
     */
    public function suppression(Aliment $aliment = null,Request $request,EntityManagerInterface $entityManager)
    {
        if($this->isCsrfTokenValid("SUP". $aliment->getId(),$request->get('_token'))){

        
        $entityManager->remove($aliment);
        $entityManager->flush();
        $this->addFlash("success", "La suppression a été effectuée");
        return $this->redirectToRoute("admin_aliment");
        }
    }
}
