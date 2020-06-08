<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends AbstractController
{
    /**
     * @Route("/admin/types", name="admin_types")
     */
    public function index(TypeRepository $repository)
    {
        $types = $repository->findAll();
        return $this->render('admin/type/adminType.html.twig', [
            'types' => $types,
        ]);
    }
    /**
     * @Route("/admin/type/create", name="ajout_types")
     * @Route("/admin/type/{id}", name="modif_types",methods="POST|GET")
     * 
     */
    public function ajoutEtModif(Type $type = null,Request $request, EntityManagerInterface $entityManager)
    {
        if(!$type){
            $type=new Type();
        }


        $form = $this->createForm(TypeType::class,$type);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($type);
            $entityManager->flush();
            $this->addFlash('success',"L'action a bien été réalisée");
            return $this->redirectToRoute("admin_types");
        }
        return $this->render('admin/type/ajoutEtModif.html.twig', [
            'type' => $type,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/type/{id}", name="sup_type",methods="delete")
     */
    public function supprimer(Type $type,EntityManagerInterface $entityManager,Request $request)
    {
      if($this->isCsrfTokenValid('SUP' .$type->getId(), $request->get('_token')))
      {
        $entityManager->remove($type);
        $entityManager->flush();
        $this->addFlash('success',"L'action a bien été réalisée");
        return $this->redirectToRoute("admin_types");

      }
    }
}
