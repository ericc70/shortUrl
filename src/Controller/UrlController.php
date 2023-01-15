<?php

namespace App\Controller;

use App\Service\UrlService;
use App\Repository\UrlRepository;
use App\Service\UrlStatisticService;
use App\Service\UrlStatisticServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @method User getUser()
 */
class UrlController extends AbstractController
{
    #[Route('/url', name: 'app_url')]
    public function index(): Response
    {
        return $this->render('url/index.html.twig', [
            'controller_name' => 'UrlController',
        ]);
    }

    #[Route('/{hash}', name: 'url_view')]
    public function view(string $hash, UrlRepository $urlRepo , UrlStatisticServices $urlStatisticService ) :Response
    {
       
        $url = $urlRepo->findOneByHash($hash);
 
       if(!$url){
        return $this->redirectToRoute('app_home');
       }

       if(!$url->getUserId()){
        return $this->redirect($url->getLongUrl());
       }


       $urlStatistic = $urlStatisticService->findOneByUrlAndDate($url, new \DateTime);
       $urlStatisticService->incrementUrlStatistic($urlStatistic);

       return $this->redirect($url->getLongUrl());

    }

    #[Route('/ajax/shorten', name: 'url_add')]
    public function add(Request $request, UrlService $urlService) : Response
    {

        $inputUrl= $request->request->get('url');

        if(!$inputUrl){
            return $this->json([
                'statusCode' => 400,
                'statusText' => "MISSING_ARG_URL"
            ]);
        }

        $domain = $urlService->parseUrl($inputUrl);
        if(!$domain){
            return $this->json([
                'statusCode' => 500,
                'statusText' => "INVALID_ARG_URL"
            ]);
        }

      $url=  $urlService->addUrl($inputUrl, $domain);

        return   $this->json([
         
            'link' => $url->getLink(),
            'inputUrl' => $url->getLongUrl()
        ]);
  
       
    }


    #[Route('/user/links', name: 'url_user_link')]
    public function list ( ) :Response
    {
        $user = $this->getUser();

        if (!$user || $user->getUrls()->count() === 0) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('url/list.html.twig', [
            'urls'  => $user->getUrls()
        ]);

    }

    #[Route('/ajax/delete', name: 'url_delete')]
    public function delete(string $hash, UrlService $urlService){
        return $urlService->deleteUrl($hash);

    }


}
