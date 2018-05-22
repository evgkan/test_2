<?php

namespace AppBundle\Controller;

use AppBundle\Entity\News;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{


    public function indexAction(Request $request)
    {
        $searchTerm = $request->get('searchTerm');
        $searchHashtag = $request->get('hashtag');

        /** @var EntityManagerInterface $em */
        $em    = $this->get('doctrine.orm.entity_manager');
        /** @var EntityRepository $repository */
        $repository = $em->getRepository('AppBundle:News');


        $qb = $repository->createQueryBuilder('a');
        // geting paginated news
        if($searchTerm)
            $qb->andWhere("a.text LIKE '%$searchTerm%'");
        if($searchHashtag)
            //searching hashtags by json format, added double quotes
            $qb->andWhere("a.hashtags LIKE '%\"$searchHashtag\"%'");

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        // getting hashtags in last 20 days
        /** @var News[] $lastNews */
        $lastNews = $repository->createQueryBuilder('b')
            ->where('b.created_at > :timerange')
            ->setParameter('timerange', time() - 60*60*24*2)
            ->orderBy('b.created_at', 'DESC')
            ->getQuery()
            ->getResult();

        // finding most popularity 10 hashtags
        $popHashtags = [];
        foreach ($lastNews as $new){
            foreach ($new->getHashtags() as $hashtag) {
                if(key_exists($hashtag, $popHashtags))
                    $popHashtags[$hashtag]++;
                else
                    $popHashtags[$hashtag] = 1;
            }
        }
        arsort($popHashtags);
        $popHashtags = array_slice($popHashtags, 0, 10);


        //search highlighting (twig "replace" is not work with variables in keys)
        if($searchTerm)
        foreach ($pagination as $item) {
            $item->setText(str_replace($searchTerm, "<span class='highlight'>$searchTerm</span>", $item->getText()));

        }

        return $this->render('default/index.html.twig', [
            'pagination' => $pagination,
            'popHashtags' => $popHashtags,
            'searchTerm' => $searchTerm,
            'searchHashtag' => $searchHashtag,
        ]);
    }


}
