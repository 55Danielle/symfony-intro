<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HttpController
 * @package App\Controller
 *
 * @Route("/http")
 * ) */
class HttpController extends AbstractController
{
    /**
     * @Route("/http", name="http")
     */
    public function index()
    {
        return $this->render('http/index.html.twig', [
            'controller_name' => 'HttpController',
        ]);
    }

    /**
     * @Route("/request")
     */
    public function request(Request $request)
    {
        //http://127.0.0.1:8000/http/request?nom=Bismuth&prenom=Danielle
        dump($_GET); // ['nom'=> 'Bismuth', 'prenom'=>'Danielle']

        //$request->query est une surcouche en objet au tableau $_GET
        dump($request->query->all());// ['nom'=> 'Bismuth', 'prenom'=>'Danielle']

        //$_GET['nom']
        dump($request->query->get('nom'));//'Bismuth'

        // optionnel : valeur par défaut si null
        dump($request->query->get('surnom', 'John Doe'));// 'John Doe'

        //isset ($_GET['surnom'])
        dump($request->query->has('surnom'));// 'False'

        // GET ou POST
        dump($request->getMethod());

        // si le formulaire en POST est envoyé
        if ($request->isMethod('POST')) { // $request->getMethod(() == 'POST'
            // $request ->request est une surcouche en objet au tableau $_POST
            dump($request->request->all());
        }

        return $this->render('http/request.html.twig');
    }

    // public function session(Request $request)
    // {
    //   // pour accéder à la session depuis l'objet Request
    // $session = $request->getSession();
    //  }

    /**
     * @param SessionInterface $session
     *
     * @Route("/session")
     */
    public function session(SessionInterface $session)
    {
        // ajouter des éléments à la session
        $session->set('nom', 'Bismuth');
        $session->set('prenom', 'Danielle');

        // les éléments stockés par l'objet Session le sont
        // dans $_SESSION['_sf2_attributes']
        dump($_SESSION);

        // tous les éléments de la session
        dump($session->all());

        //accéder à un élément de la session
        dump($session->get('prenom'));

        // supprimer  un élément de la session
        $session->remove('nom');

        dump($session->all());

        // vider la session
        $session->clear();

        dump($session->all());

        $session->set('user', ['prenom' => 'Groucho', 'nom' => 'Marx']);

        dump($session->all());

        return $this->render('http/session.html.twig');
    }

    /**
     * @Route("/response")
     */
    public function response(Request $request)
    {
        // toutes les méthodes des contrôleurs doivent retourner
        // un objet instance de la classe Response
        $response = new Response('Contenu de la page');

        if ($request->query->get('type') == 'twig') {
            //
            //$this->>render() retourne un objet instance dde la classe Reponse
            // dont le contenu est le HTML construit par le template
            return $this->render('http/response.html.twig');
        } elseif ($request->query->get('type') == 'json') {
            $response = [
                'nom' => 'Marx',
                'prenom' => 'Groucho'
            ];

            //return new Response(json_encode($response));

            // encode le tableau $reponse en json
            // et retourne une réponse avec l'entête HTTP Content-Type application/json

            return new JsonResponse($response);
        } elseif ($request->query->get('found') == 'no') {
            // pour retourner une 404, on jette cette exception
            throw new NotFoundHttpException();
            // http://127.0.0.1:8000/http/response?redirect=index
        } elseif ($request->query->get('redirect') == 'index') {
            // redirection en passant le nom de la route de la page :
            // app_index_index : IndexController::index()
            return $this->redirectToRoute('app_index_index');
        } elseif ($request->query->get('redirect') == 'bonjour') {
            // redirection vers une route qui contient une partie variable :
            return $this->redirectToRoute(
                'app_index_bonjour',
                [
                    'qui' => "le monde"
                ]
            );
        }

        return $response;
    }

    /**
     * @Route("/flash")
     */
    public function flash()
    {
        // enregistre un message dans la session
        $this->addFlash('success', 'Message de confirmation');
        $this->addFlash('success', 'Autre message de confirmation');
        $this->addFlash('error', "Message d'erreur");

        return $this->redirectToRoute('app_http_flashed');
    }

    /**
     * @Route("/flashed")
     */
    public function flashed(SessionInterface $session)
    {
        //dump($_SESSION);

//        foreach ($session->getFlashBag()->all() as $type => $messages) {
//            echo "<strong>$type:</strong><br>";
////            foreach ($messages as $message) {
////                echo $message . '<br>';
////            }
//            echo implode('<br>', $messages);
//            echo '<br>';
//        }

        return $this->render('http/flashed.html.twig');

    }

}
