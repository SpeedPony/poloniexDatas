<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 03/02/2018
 * Time: 15:52
 */

namespace App\VO;


class TimeVO {

    /**
     * @var string
     */
    private $pouc;

    /**
     * @var string
     */
    private $brut;

    /**
     * @var string
     */
    private $class;

    /**
     * @return string
     */
    public function getPouc() {
        return $this->pouc;
    }

    /**
     * @param string $pouc
     */
    public function setPouc(string $pouc): void {
        $this->pouc = $pouc;
    }

    /**
     * @return string
     */
    public function getBrut(): string {
        return $this->brut;
    }

    /**
     * @param string $brut
     */
    public function setBrut(string $brut): void {
        $this->brut = $brut;
    }

    /**
     * @return string
     */
    public function getClass(): string {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass(string $class): void {
        $this->class = $class;
    }
}