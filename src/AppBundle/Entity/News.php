<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="news")
 */
class News
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $twitter_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     */
    private $hashtags;

    /**
     * @ORM\Column(type="integer")
     */
    private $created_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $publicated_at;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return News
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdAt
     *
     * @param integer $createdAt
     *
     * @return News
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return integer
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set publicatedAt
     *
     * @param integer $publicatedAt
     *
     * @return News
     */
    public function setPublicatedAt($publicatedAt)
    {
        $this->publicated_at = $publicatedAt;

        return $this;
    }

    /**
     * Get publicatedAt
     *
     * @return integer
     */
    public function getPublicatedAt()
    {
        return $this->publicated_at;
    }

    /**
     * Set hashtags
     *
     * @param string $hashtags
     *
     * @return News
     */
    public function setHashtags($hashtags)
    {
        $this->hashtags = json_encode($hashtags);

        return $this;
    }

    /**
     * Get hashtags
     *
     * @return array
     */
    public function getHashtags()
    {
        $arr = (array) json_decode($this->hashtags);
        $result = [];
        foreach ($arr as $item)
            if (key_exists('text', $item))
                $result[] = $item->text;
        return $result;
    }

    /**
     * Set twitterId
     *
     * @param integer $twitterId
     *
     * @return News
     */
    public function setTwitterId($twitterId)
    {
        $this->twitter_id = $twitterId;

        return $this;
    }

    /**
     * Get twitterId
     *
     * @return integer
     */
    public function getTwitterId()
    {
        return $this->twitter_id;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return News
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }



}
