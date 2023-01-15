<?php

namespace App\Service;

use App\Entity\Url;
use App\Entity\UrlStatistic;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UrlStatisticRepository;

class UrlStatisticServices
{

    private EntityManagerInterface $em;
    private UrlStatisticRepository $urlStatisticRepo;
  

    public function __construct(EntityManagerInterface $em, UrlStatisticRepository $urlStatisticRepo)
    {
        $this->em = $em;
        $this->urlStatisticRepo = $urlStatisticRepo;
    }

    public function findOneByUrlAndDate(Url $url, \DateTimeInterface $date): UrlStatistic
    {
        $urlStatistic = $this->urlStatisticRepo->findOneBy([
            'url' => $url,
            'date' => $date
        ]);

        if (!$urlStatistic) {
            $urlStatistic = new urlStatistic;
            $urlStatistic->setUrl($url);
            $urlStatistic->setDate($date);
        }

        return $urlStatistic;
    }

    public function incrementUrlStatistic(UrlStatistic $urlStatistic): UrlStatistic
    {
        $urlStatistic->setClicks($urlStatistic->getClicks() + 1);

        $this->em->persist($urlStatistic);
        $this->em->flush();

        return $urlStatistic;
    }

}