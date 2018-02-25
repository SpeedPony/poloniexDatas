<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 03/02/2018
 * Time: 15:51
 */

namespace App\VO;

class MainVO {

    /**
     * @var string
     */
    private $pair;

    /**
     * @var TimeVO[]
     */
    private $datas;

    /**
     * @return string
     */
    public function getPair(): string {
        return $this->pair;
    }

    /**
     * @param string $pair
     */
    public function setPair(string $pair): void {
        $this->pair = $pair;
    }

    /**
     * @return TimeVO[]
     */
    public function getDatas() {
        return $this->datas;
    }

    /**
     * @param TimeVO $data
     * @param int $key
     */
    public function addData(TimeVO $data, $key): void {
        $this->datas[$key] = $data;
    }

}