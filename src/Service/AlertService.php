<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 27/02/2018
 * Time: 22:26
 */

namespace App\Service;

use App\Repository\AlertRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AlertService
 * @package App\Service
 */
class AlertService {

    /**
     * @var AlertRepository
     */
    private $alertRepository;

    /**
     * AlertService constructor.
     * @param AlertRepository $alertRepository
     */
    public function __construct(AlertRepository $alertRepository) {
        $this->alertRepository = $alertRepository;
    }

    public function getDatas(Request $request) {
        if(is_null($request->get('minDate'))) {
           $minDate = new \DateTime('2017-01-01');
        }
        if(is_null($request->get('maxDate'))) {
            $maxDate = new \DateTime('2022-01-01');
        }
        return $this->alertRepository->getDatas($minDate, $maxDate);
    }
}