<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Pieces;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PiecesController extends AbstractController
{
    #[Route('/new', name: 'app_newPiece')]
    public function newAction(Request $request, EntityManagerInterface $em): Response
    {
        $piece = new Pieces();

        $form = $this->createFormBuilder($piece)
            ->add('nom_piece', TextType::class, ['label' => 'Titre'])
            ->add('description_piece', TextareaType::class, ['label' => 'Description'])
            ->add('save', SubmitType::class, [
                'label' => 'Créer',
                'attr' => ['class' => 'btn btn-success mt-3']
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($piece);

            // Récupérer tous les inputs commençant par "photo_"
            foreach ($request->request->all() as $key => $value) {
                if (str_starts_with($key, 'photo_') && $value) {
                    $photo = new Photo();
                    $photo->setLienPhoto($value);
                    $photo->setPiece($piece); // lie la photo à la pièce
                    $em->persist($photo);     // persister la photo
                    $piece->addPhoto($photo); // ajoute à la collection
                }
            }

            $em->flush();

            $this->addFlash('success', 'Nouvelle pièce créée avec succès !');
            return $this->redirectToRoute('app_allPieces');
        }



        return $this->render('pieces/new.html.twig', [
            'form' => $form->createView(),
            'laPiece' => $piece,
        ]);
    }


    #[Route('/pieces/{id_piece}', name: 'app_pieces')]
    public function viewPieces(int $id_piece, EntityManagerInterface $em): Response
    {
        $piece = $em->getRepository(Pieces::class)->find($id_piece);

        if (!$piece) {
            throw $this->createNotFoundException(
                'Aucune pièce pour l\'id: ' . $id_piece
            );
        }

        return $this->render('pieces/view.html.twig', [
            'piece' => $piece
        ]);
    }
    #[Route('/all', name: 'app_allPieces')]
    public function viewAll(EntityManagerInterface $em): Response
    {
        $pieces = $em->getRepository(Pieces::class);
        $pieces = $pieces->findBy([], ['id_piece' => 'ASC']);


        return $this->render('pieces/all.html.twig', [
            'lesPieces' => $pieces
        ]);
    }

    #[Route('/delete/{id_piece}', name: 'app_deletePiece')]
    public function deleteAction(int $id_piece, EntityManagerInterface $em): RedirectResponse
    {
        $piece = $em->getRepository(Pieces::class)->find($id_piece);

        $em->remove($piece);
        $em->flush();

        $this->addFlash('success', 'Pièce supprimé avec succès !');

        return $this->redirectToRoute('app_allPieces');
    }

    #[Route('/edit/{id_piece}', name: 'app_editPiece')]
    public function updateAction(Request $request, int $id_piece, EntityManagerInterface $em): Response
    {

        $piece = $em->getRepository(Pieces::class)->find($id_piece);

        if (!$piece) {
            throw $this->createNotFoundException('Aucune pièce trouvée avec l\'id : ' . $id_piece);
        }

        $form = $this->createFormBuilder($piece)
            ->add('nom_piece', TextType::class, ['label' => 'Titre'])
            ->add('description_piece', TextareaType::class, ['label' => 'Description'])
            ->add('save', SubmitType::class, ['label' => 'Éditer', 'attr' => ['class' => 'btn btn-primary mt-3']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($piece->getPhotos() as $photo) {
                $photoLink = $request->request->get('photo_' . $photo->getId());
                if ($photoLink !== null) {
                    $photo->setLienPhoto($photoLink);
                }
            }

            $em->flush();

            $this->addFlash('success', 'Pièce mise à jour avec succès !');

            return $this->redirectToRoute('app_allPieces');
        }

        // Rendu du formulaire
        return $this->render('pieces/edit.html.twig', [
            'form' => $form->createView(),
            'laPiece' => $piece,
        ]);
    }
}
