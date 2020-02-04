<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     * @return
     */
    public function index()
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * localhost:8000/hello
     *
     * URL de la page
     * @Route("/hello")
     */
    public function hello()
    {
    // rendu du fichier qui construit le html contenu dans la page
        //chemin à partir de la racine du répertoire templates
        return $this->render('index/hello.html.twig');
    }

    /**
     *
     * Une route avec une partie variable (entre accolades)
     * Le $qui en paramètre de la méthode (même nom que dans la route)
     * contient la valeur de cette partie variable.
     * @Route("/bonjour/{qui}")
     *
     */
    public function bonjour($qui)
    {
        //echo $qui;
       return $this->render(
           'index/bonjour.html.twig',
           [
               'nom' => $qui
           ]
       );
    }
    /**
     * Partie variable de la route optionnelle (avec une valeur par défaut) :
     * La route matche /salut/unNom : $qui vaut "unNom"
     * et matche aussi /salut et /salut/ : $qui vaut "à toi"
     *
     * @Route("/salut/{qui}", defaults={"qui": "à toi"})
     */
    public function salut($qui)
    {

        return $this->render(
            'index/salut.html.twig',
            [
                'qui' => $qui
            ]
        );


    }

    /**
     * id doit être un nombre (\d+ en expression régulière)
     *
     * @Route ("/categorie/{id}", requirements={"id": "\d+"})
     */
    public function categorie($id)
    {
        return $this->render(
            'index/categorie.html.twig',
            [
                'id' => $id
            ]
        );
    }
}


