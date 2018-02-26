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
 * @ORM\Entity(repositoryClass="App\Repository\PairRepository")
 */
class Pair
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     * @var boolean
     */
    private $mailSent;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $dateMail;

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
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isMailSent() {
        return $this->mailSent;
    }

    /**
     * @param bool $mailSent
     */
    public function setMailSent(bool $mailSent): void {
        $this->mailSent = $mailSent;
    }

    /**
     * @return \DateTime
     */
    public function getDateMail() {
        return $this->dateMail;
    }

    /**
     * @param \DateTime $dateMail
     */
    public function setDateMail($dateMail): void {
        $this->dateMail = $dateMail;
    }
}