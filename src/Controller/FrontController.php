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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends Controller
{
    /**
     * @Route("/getDatas", name="poloniex_get_datas")
     */
    public function getDatas() {
        $datas = $this->get('poloniex.api_service')->callPoloniexApi();
        if(!is_null($datas)) {
            $this->get('poloniex.datas_service')->saveDatas($datas);
        }
        return new JsonResponse(true);
    }

    /**
     * @Route("/", name="poloniex_front")
     */
    public function front() {
        $numbers = array(10,5,3);
        $datas = $this->get('poloniex.datas_service')->getDatas($numbers);
        $datas = $this->get('poloniex.datas_service')->formatDatas($datas);
        return $this->render('base.html.twig', array('datas' => $datas, 'numbers' => $numbers));
    }
}