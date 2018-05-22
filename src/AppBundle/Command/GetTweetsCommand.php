<?php

namespace AppBundle\Command;

use Abraham\TwitterOAuth\TwitterOAuth;
use AppBundle\Entity\News;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetTweetsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this->setName('tweets:get')->setDescription('Getting last twets');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get('doctrine')->getManager();

        /** @var EntityRepository $repository */
        $repository = $em->getRepository('AppBundle:News');
        /** @var News $lastNews */
        $lastNews = $repository->findOneBy([], ['twitter_id' => 'DESC']);
        if ($lastNews)
            $twitterQueryParams = ["since_id" => $lastNews->getTwitterId()];
        else
            $twitterQueryParams = ["count" => 200];

        $connection = new TwitterOAuth(
            $container->getParameter('twt_consumer_key'),
            $container->getParameter('twt_consumer_secret'),
            $container->getParameter('twt_oauth_token'),
            $container->getParameter('twt_oauth_token_secret')
        );

        $twitterQueryParams["screen_name"] = $container->getParameter('twt_screen_name');
        $twitterQueryParams["exclude_replies"] = true;

        $tweets = $connection->get("statuses/user_timeline", $twitterQueryParams);

        if(!is_array($tweets))
            throw new \Exception('No data');

        $tweets = array_reverse($tweets);
        //var_dump($tweets); exit;
        foreach ($tweets as $tweet) {
            $news = new News();
            $news->setTwitterId($tweet->id);
            $news->setText($tweet->text);
            $date = \DateTime::createFromFormat('D M d H:i:s T Y' , $tweet->created_at);
            $news->setCreatedAt($date->getTimestamp());
            $news->setPublicatedAt(time());
            $news->setHashtags($tweet->entities->hashtags);
            $news->setImage('');

            $em->persist($news);
            $em->flush();
        }

        $output->writeln("Find " . count($tweets) . " new tweets");
    }
}