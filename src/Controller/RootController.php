<?php

namespace App\Controller;

use App\Form\CharacterFormType;
use App\Repository\CharacterRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RootController extends AbstractController
{
    private EntityManagerInterface $em;
    private CharacterRepository $characterRepository;
    private MovieRepository $movieRepository;

    public function __construct(
        EntityManagerInterface $em,
        CharacterRepository $characterRepository,
        MovieRepository $movieRepository
    ) {
        $this->em = $em;
        $this->characterRepository = $characterRepository;
        $this->movieRepository = $movieRepository;
    }

    #[Route('/', name: 'character_index')]
    public function index(): Response
    {
        $characters = $this->characterRepository->findAll();

        return $this->render('character_index.html.twig', [
            'characters' => $characters,
        ]);
    }

    #[Route('/characters/{characterId}/edit', name: 'character_edit')]
    public function edit_character(Request $request, $characterId): Response
    {
        $character = $this->characterRepository->find($characterId);

        if (!$character) {
            throw $this->createNotFoundException('Character not found');
        }

        $form = $this->createForm(CharacterFormType::class, $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $character = $form->getData();

            $picture = $form->get('picture')->getData();

            if ($picture) {
                $pictureName = md5(uniqid()) . '.' . $picture->guessExtension();
                $picture->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads',
                    $pictureName
                );

                $character->setPicture($pictureName);
            }

            $this->em->persist($character);
            $this->em->flush();

            $this->addFlash('success', 'Character updated successfully!');
            return $this->redirect($request->getUri());
        }

        $character = $this->characterRepository->find($characterId);

        return $this->render('character_edit.html.twig', [
            'character' => $character,
            'picture' => $character->getPicture(),
            'form' => $form,
        ]);
    }

    #[Route('/characters/{characterId}', name: 'character_delete', methods: ['DELETE'])]
    public function delete_character($characterId): Response
    {
        $character = $this->characterRepository->find($characterId);

        $this->em->remove($character);
        $this->em->flush();

        return $this->redirectToRoute('character_index');
    }

    #[Route('/movies', name: 'movie_index')]
    public function index_movie(): Response
    {
        // TODO
        return $this->render('movie_index.html.twig', []);
    }

    #[Route('/movies/{movieId}', name: 'movie_show')]
    public function show_movie(): Response
    {
        // TODO
        return $this->render('movie_show.html.twig', []);
    }
}
