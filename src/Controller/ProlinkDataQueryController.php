<?php
namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProlinkDataQueryController extends AbstractController {

    /**
     * @Rest\Get("/CZ/pt/{part_no}")
     * @param $part_no
     * @return JsonResponse
     */
    public function isPartNumExist($part_no): JsonResponse {
        $conn = $this->getDoctrine()->getConnection();

        $num = $conn->fetchColumn('SELECT count(*) as num FROM pt_mstr WHERE pt_part = ?', [$part_no]);
        if($num > 0){
            $exist = true;
        }else{
            $exist = false;
        }
        $restresult = [
            'msg' => 'Data fetched successfully',
            'data' => ['exist'=>$exist],
            'status' => true
        ];

        return new JsonResponse($restresult);
    }

    /**
     * @Rest\Get("/SMT/pt/{part_no}")
     * @param $part_no
     * @return JsonResponse
     */
    public function isPartNumExistSMT($part_no): JsonResponse {
        $conn = $this->getDoctrine()->getConnection('customer');

        $num = $conn->fetchColumn('SELECT count(*) as num FROM pt_mstr WHERE pt_part = ?', [$part_no]);
        if($num > 0){
            $exist = true;
        }else{
            $exist = false;
        }
        $restresult = [
            'msg' => 'Data fetched successfully',
            'data' => ['exist'=>$exist],
            'status' => true
        ];

        return new JsonResponse($restresult);
    }

    /**
     * @Rest\Get("/CQ/pt/{part_no}")
     * @param $part_no
     * @return JsonResponse
     */
    public function isPartNumExistCQ($part_no): JsonResponse {
        $conn = $this->getDoctrine()->getConnection('customer');

        $num = $conn->fetchColumn('SELECT count(*) as num FROM pt_mstr WHERE pt_part = ?', [$part_no]);
        if($num > 0){
            $exist = true;
        }else{
            $exist = false;
        }
        $restresult = [
            'msg' => 'Data fetched successfully',
            'data' => ['exist'=>$exist],
            'status' => true
        ];

        return new JsonResponse($restresult);
    }

    /**
     * @Rest\Get("/CZ/part_data/{par_no}")
     * @param $par_no
     * @return JsonResponse
     */
    public function getBOMData($par_no): JsonResponse {
        $conn = $this->getDoctrine()->getConnection();

        $part_detail = $conn->fetchAll('SELECT pt_part,pt_um,pt_desc1,pt_prod_line,pt_status FROM pt_mstr WHERE pt_part like ?', ['%'.$par_no.'%']);

        if(!empty($part_detail)) {
            $restresult = [
                'msg' => 'Data fetched successfully',
                'data' => $part_detail,
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
     * @Rest\Get("/CZ/pl/{product_line}")
     * @param $product_line
     * @return JsonResponse
     */
    public function getProductLine($product_line): JsonResponse {
        $conn = $this->getDoctrine()->getConnection();

        $pl_list = $conn->fetchAll('SELECT pl_prod_line,pl_desc FROM pl_mstr WHERE pl_prod_line like ?', ['%'.$product_line.'%']);

        $restresult = [
            'msg' => 'Data fetched successfully',
            'data' => $pl_list,
            'status' => true
        ];

        return new JsonResponse($restresult);
    }

    /**
     * @Rest\Get("/CZ/supplier/getAllData")
     * @return JsonResponse
     */
    public function getSupplierList(): JsonResponse {
        $conn = $this->getDoctrine()->getConnection();

        $supplier_list = $conn->fetchAll("SELECT vd_addr as s_code,vd_sort as s_name FROM vd_mstr WHERE vd_sort <> '' ");

        $restresult = [
            'msg' => 'Data fetched successfully',
            'data' => $supplier_list,
            'status' => true
        ];

        return new JsonResponse($restresult);
    }

    /**
     * @Rest\Get("/CZ/supplier/getData/{supplier_name}")
     * @param $supplier_name
     * @return JsonResponse
     */
    public function isSupplierExist($supplier_name): JsonResponse {
        $conn = $this->getDoctrine()->getConnection();

        $num = $conn->fetchColumn('SELECT count(*) as num FROM vd_mstr WHERE vd_sort = ?', [$supplier_name]);
        if($num > 0){
            $exist = true;
        }else{
            $exist = false;
        }
        $restresult = [
            'msg' => 'Data fetched successfully',
            'data' => ['exist'=>$exist],
            'status' => true
        ];

        return new JsonResponse($restresult);
    }

    /**
     * @Rest\Get("/CZ/ecp_data/{par_no}")
     * @param $par_no
     * @return JsonResponse
     */
    public function getECPData($par_no): JsonResponse {
        $conn = $this->getDoctrine()->getConnection();

        $part_all = $conn->fetchAll("SELECT pt_part FROM pt_mstr 
            WHERE pt_part LIKE ? AND pt_part NOT LIKE '%SYN%'
              AND (
                   pt_part LIKE '%ECP%' 
                OR pt_part LIKE '%HW%' 
                OR pt_part LIKE '%FW%' 
                OR pt_part LIKE '%CFG%'
              ) order by pt_part;", ['%'.$par_no.'%']);

        $keywords = ["HW", "FW"];
        $latestVersions = [];
        $noKeywordItems = [];

        foreach ($part_all as $item_arr) {
            $item = $item_arr['pt_part'];
            $found = false;

            foreach ($keywords as $keyword) {
                $pos = stripos($item, $keyword);

                if ($pos !== false) {
                    $found = true;
                    $prefix = substr($item, 0, $pos);
                    $prefix = strtoupper($prefix);
                    $versionPart = substr($item, $pos + strlen($keyword));
                    $version = (int)preg_replace('/[^0-9]/', '', $versionPart);

                    if (!isset($latestVersions[$keyword][$prefix]) || $version > $latestVersions[$keyword][$prefix]['version']) {
                        $latestVersions[$keyword][$prefix] = [
                            'part' => $item,
                            'version' => $version
                        ];
                    }
                }
            }

            if (!$found) {
                $noKeywordItems[] = $item;
            }
        }

        $filteredResults = [];
        foreach ($latestVersions as $keyword => $items) {
            $filteredResults = array_merge($filteredResults, array_column($items, 'part'));
        }

        $finalResult = array_merge($filteredResults, $noKeywordItems);

        if(!empty($finalResult)) {
            $restresult = [
                'msg' => 'Data fetched successfully',
                'data' => $finalResult,
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