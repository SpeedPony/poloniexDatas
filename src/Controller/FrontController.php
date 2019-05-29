<?php
/**
 * Created by PhpStorm.
 * User: Speed
 * Date: 03/02/2018
 * Time: 01:20
 */

namespace App\Controller;

use App\Service\APIService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Constant\TimeConstant;

/**
 * Class FrontController
 * @package App\Controller
 */
class FrontController extends Controller
{
    /**
     * @Route("/", name="poloniex_front")
     */
    public function front() {
        $numbers = TimeConstant::FONTTIME;
        $datas = $this->get('poloniex.datas_service')->getDatas($numbers);
        $datas = $this->get('poloniex.datas_service')->formatDatas($datas);
        return $this->render('front.html.twig', array('datas' => $datas, 'numbers' => $numbers));
    }
}