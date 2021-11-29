<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Movie;
use App\Repository\UserRepository;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


class FavoriteMovieController
{
    public function __invoke(int $id, EntityManagerInterface $em, MovieRepository $movieRepository, Request $request): User
    {
        // A modifier car ne rajoute pas mais remplace un favori
        $user = new User();
        $user = $em->getRepository(User::class)->find($id);
        $user->getFavorite();
        $em->flush();

        return $user ;
    }
}