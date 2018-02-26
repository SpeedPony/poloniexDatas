<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 03/02/2018
 * Time: 01:22
 */

// src/Entity/Product.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DatasRepository")
 */
class Datas
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="bigint")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @var string
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pair", inversedBy="id")
     * @var Pair
     */
    private $pair;

    /**
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $position;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getValue(): string {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void {
        $this->value = $value;
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
     * @return int
     */
    public function getPosition(): int {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void {
        $this->position = $position;
    }
}