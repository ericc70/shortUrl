<?php

namespace App\Service;

use App\Entity\Url;
use App\Repository\UrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class UrlService{

    private EntityManagerInterface $em;
    private Security $security;

    public function __construct(EntityManagerInterface $em,,UrlRepository $urlRepo ,Security $security){
        $this->em = $em;
        $this->security = $security;
        $this->urlRepo = $urlRepo;

    }

    public function addUrl(string $inputUrl, string $domain )
    {
        $url = new Url();
      
        $user = $this->security->getUser();


        $hash = $this->generateHash();
        $link = $_SERVER['HTTP_ORIGIN'] . "/$hash";

        $url->setLongUrl($inputUrl);
        $url->setDomain($domain);
        $url->setHash( $hash );
        $url->setLink($link);
        $url->setCreatedAt(new \DateTime);
        $url->setUserId($user);
       
        $this->em->persist($url);
        $this->em->flush();


        return $url;
    }

    public function parseUrl(string $url): string | bool
    {

        $domain = parse_url($url, PHP_URL_HOST );
        if (!$domain) return false;
        /* verification ip sur domaine*/
        if(!filter_var(gethostbyname($domain), FILTER_VALIDATE_IP)) return false;

        return $domain;



    }

    public function generateHash(int $offset = 0, int $length =10 ):String
    {
        return substr(md5(uniqid(mt_rand(), true)), $offset, $length);
    }

    public function deleteUrl(string $hash) :Response
    {
        $url = $this->urlRepo->findOneBy(['hash' => $hash]);

        if (!$url) {
            return new JsonResponse([
                'statusCode' => 'URL_NOT_FOUND',
                'statusText' => "Le lien n'a pas été trouvé !"
            ]);
        }

        $this->em->remove($url);
        $this->em->flush();

        return new JsonResponse([
            'statusCode' => 'DELETE_SUCCESSFUL',
            'statusText' => 'Le lien a bien été supprimé !'
        ]);
    }
}