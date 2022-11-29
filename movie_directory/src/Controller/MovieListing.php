<?php

namespace Drupal\movie_directory\Controller;

use Drupal\Core\Controller\ControllerBase;

class MovieListing extends ControllerBase{
    public function view() {

        $this->listMovies();

        $content = [];
        $content['name'] = 'My name is Marcelo';
        $content['movies'] = $this->createMovieCard();

        return [
            '#theme' => 'movie-listing',
            '#content' => $content,
        ];

        
    }

    public function listMovies() {
        $movie_api_conncetor_sevice = \Drupal::service('movie_directory.api_connector');
        $movies_list = $movie_api_conncetor_sevice->discoverMovies();

        if(!empty($movies_list->results)) {
            return $movies_list->results;
        }
        return [];
    }

    public function createMovieCard() {
        $movie_api_connector_service = \Drupal::service('movie_directory.api_connector');
        $movie_cards = [];

        $movies = $this->listMovies();
        
        if (!empty($movies)) {
            foreach ($movies as $movie) {
                $content = [
                    'title' => $movie->title,
                    'description' => $movie->overview,
                    'movie_id' => $movie->id,
                    'image' => $movie_api_connector_service->getImageUrl($movie->poster_path)
                ];

                $movie_cards[] = [
                    '#theme' => 'movie-card',
                    '#content' => $content,
                ];
            }
        }
        return $movie_cards;
    }
     
}