<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Player
 *
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlayerRepository")
 * @Serializer\ExclusionPolicy("all")
 */
class Player
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Please enter a clever nickname")
     * @Serializer\Expose
     *
     * @ORM\Column(name="nickname", type="string", length=255, unique=true)
     */
    private $nickname;

    /**
     * @var int
     *
     * @Serializer\Expose
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var string
     *
     * @Serializer\Expose
     *
     * @ORM\Column(name="tagLine", type="string", length=255)
     */
    private $tagLine;

    public function __construct($nickname = null, $position = null)
    {
        $this->nickname = $nickname;
        $this->position = $position;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return Player
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set position
     *
     * @param string position
     *
     * @return Player
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set tagLine
     *
     * @param string $tagLine
     *
     * @return Player
     */
    public function setTagLine($tagLine)
    {
        $this->tagLine = $tagLine;

        return $this;
    }

    /**
     * Get tagLine
     *
     * @return string
     */
    public function getTagLine()
    {
        return $this->tagLine;
    }
}

