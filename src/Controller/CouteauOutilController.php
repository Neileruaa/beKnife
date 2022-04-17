<?php

namespace App\Controller;

use App\Entity\Couteau;
use App\Entity\CouteauOutil;
use App\Repository\CouteauOutilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CouteauOutilController extends AbstractController
{
    /**
     * @Route("/couteau/outil/custom/{id}", name="app_couteau_outil")
     */
    public function choixCarac(Couteau $couteau, Request $request, CouteauOutilRepository $couteauOutilRepository): Response
    {
        $couteauOutils = $couteauOutilRepository->findBy(['couteau' => $couteau]);
        return $this->render('couteau_outil/choixCarac.html.twig', [
            'couteauOutils' => $couteauOutils,
            'couteau' => $couteau
        ]);
    }

    /**
     * @Route("/couteau/outil/editDg/{id}", name="app_couteau_outil_editdroitegauche")
     */
    public function editDroiteGauche(CouteauOutil $couteauOutil, Request $request, EntityManagerInterface $entityManager, CouteauOutilRepository $couteauOutilRepository): Response
    {
        if($request->get("dorg") == "droite") {
            $couteauOutil->setIsDroite(true);
            $couteauOutil->setIsGauche(false);
        } else {
            $couteauOutil->setIsDroite(false);
            $couteauOutil->setIsGauche(true);
        }
        $entityManager->persist($couteauOutil);
        $entityManager->flush();
        $couteauOutils = $couteauOutilRepository->findBy(['couteau' => $couteauOutil->getCouteau()]);
        return $this->render('couteau_outil/choixCarac.html.twig', [
            'couteauOutils' => $couteauOutils,
            'couteau' => $couteauOutil->getCouteau()
        ]);
    }
}
