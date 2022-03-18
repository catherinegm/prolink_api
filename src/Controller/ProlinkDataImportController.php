<?php


namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProlinkDataImportController extends AbstractController {


    /**
     * @Route ("/")
     */
    public function homePage() {
        return new Response('BOS API home page');
    }

    /**
 * @Rest\Get("/CZ/bom/getAllBOMdata")
 * @return JsonResponse
 */
    public function getAllBOMdata(): JsonResponse {
        $conn = $this->getDoctrine()->getConnection();

        $keep_date = date("Y-m-d",strtotime("2017-01-01"));
        $bom_list = $conn->fetchAll('select ps_par,ps_comp,ps_start,ps_end,ps_qty_per,ps_ps_code,ps_rmks,ps_op,ps_mod_date from ps_mstr where ps_end IS NULL OR ps_end > ?',[$keep_date]);

        if(!empty($bom_list)) {
            $restresult = [
                'msg' => 'Data fetched successfully',
                'data' => $bom_list,
                'status' => true,
            ];
        } else {
            $restresult = [
                'msg' => 'Not found',
                'data' => [],
                'status' => false,
            ];
        }
        return new JsonResponse($restresult);
    }

    /**
     * @Rest\Get("/CZ/bom/getModifiedBOMdata")
     * @return JsonResponse
     */
    public function getModifiedBOMdata(): JsonResponse {
        $conn = $this->getDoctrine()->getConnection();

        $yesterday_date = date("Y-m-d",strtotime("-1 day"));
        //$yesterday_date = date("Y-m-d",strtotime("2022-3-11"));
        $modified_bom_list = $conn->fetchAll('select ps_par,ps_comp,ps_start,ps_end,ps_qty_per,ps_ps_code,ps_rmks,ps_op,ps_mod_date from ps_mstr where ps_mod_date >= ?',[$yesterday_date]);

        if(!empty($modified_bom_list)) {
            $restresult = [
                'msg' => 'Data fetched successfully',
                'data' => $modified_bom_list,
                'status' => true,
            ];
        } else {
            $restresult = [
                'msg' => 'Not found',
                'data' => [],
                'status' => false,
            ];
        }
        return new JsonResponse($restresult);
    }


}