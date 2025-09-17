<?php

namespace App\Controller;

use App\Entity\Pieces;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'accueil')] // ← ici, '/' est la home page
    public function piece(EntityManagerInterface $detail): Response
    {
        $repository = $detail->getRepository(Pieces::class);
        $details = $repository->findBy([],['id_piece' => 'ASC']);

        return $this->render('accueil/accueil.html.twig', [
            'leDetail' => $details,    
        ]);
    }
   
     #[Route('/accueil', name: 'nav_accueil')] // ← ici, '/' est la home page
    public function nav_acceuil(): Response
    {


        return $this->render('activite/activites.html.twig', [
              
        ]);
    }
}