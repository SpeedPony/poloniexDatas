<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 27/02/2018
 * Time: 21:29
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlertRepository")
 */
class Alert {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pair", inversedBy="alert")
     * @var Pair
     */
    private $pair;

    /**
     * @return Pair
     */
    public function getPair(): Pair {
        return $this->pair;
    }

    /**
     * @param Pair $pair
     */
    public function setPair(Pair $pair): void {
        $this->pair = $pair;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void {
        $this->date = $date;
    }
}