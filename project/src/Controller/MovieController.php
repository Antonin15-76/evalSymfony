<?php

namespace App\Controller;

use App\Entity\MovieSerie;
use App\Form\MovieAddType;
use App\Repository\MovieSerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie", name="movie")
     */
    public function index(MovieSerieRepository $repo): Response
    {
        $movies = $repo->findAll();
       
        return $this->render('movie/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/movie/{id}", name="movie_details")
     */
    public function getMovieSerieById(MovieSerieRepository $repo): Response
    {
        $movies = $repo->findAll();
       
        return $this->render('movie/details/index.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * @Route("/movieSerie/new", name="movieSerie_new")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param MovieSerieRepository $maireRepository
     * @return Response
     */
    public function newMovieSerie(Request $request, EntityManagerInterface $manager, MovieSerieRepository $maireRepository)
    {
        $movieSerie = new MovieSerie();

        $form = $this->createForm(MovieAddType::class, $movieSerie);

        $form->handleRequest($request);
        // var_dump($form);

        if($form->isSubmitted() && $form->isValid()) {
            // echo $form->nom();
            var_dump($form->isSubmitted());
            // dd($form->{'viewData'});
            $result = $form->getData();
            // dd($result->{'Nom'});
            $movieSerie->setNom($result->{'Nom'});
            $movieSerie->setSynopsis($result->{'Synopsis'});

            $movieSerie->setType($result->{'Type'});
            $movieSerie->setDateCreation($result->{'Date_creation'});

            $manager->persist($movieSerie);
            $manager->flush();

           return $this->redirectToRoute("movie");
        }
        return $this->render('movie/newMovieSerie.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
