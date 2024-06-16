<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SwapiService
{
    private $em = null;
    private $client = null;
    private $charater_url = 'https://swapi.dev/api/people/';
    private $movie_url = 'https://swapi.dev/api/films/';

    public function __construct(EntityManagerInterface $em, HttpClientInterface $client)
    {
        $this->em = $em;
        $this->client = $client;
    }

    public function import()
    {
        try {
            // get movies
            $movie_response = $this->client->request('GET', $this->movie_url);
            $movies = $movie_response->toArray()['results'];

            $characters = [];

            // get the first 3 pages of characters
            foreach ([1, 2, 3] as $page) {
                $character_response = $this->client->request('GET', "{$this->charater_url}?page={$page}");
                $characters = array_merge($characters, $character_response->toArray()['results']);
            }

            // build a map of swapi urls to internal ids
            $url_id_map = [];

            // persist movies to db
            foreach ($movies as $movie) {
                $movie_entity = new Movie();
                $movie_entity->setName($movie['title']);
                $this->em->persist($movie_entity);

                $url_id_map[$movie['url']] = $movie_entity->getId();
            }

            // persist characters to db
            foreach ($characters as $character) {
                $character_entity = new Character();
                $character_entity->setName($character['name']);
                $character_entity->setGender($character['gender'] ?? '');
                $character_entity->setHeight($character['height'] ?? '');
                $character_entity->setMass($character['mass'] ?? '');

                $movie_urls = $character['films'];

                // add movies to character
                foreach ($movie_urls as $movie_url) {
                    $movie_id = $url_id_map[$movie_url];
                    // TODO: investigate if it can be added just with the id for performance
                    $movie = $this->em->getRepository(Movie::class)->find($movie_id);
                    $character_entity->addMovie($movie);
                }

                $this->em->persist($character_entity);
            }

            $this->em->flush();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
