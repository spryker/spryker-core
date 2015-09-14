<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;
use SprykerFeature\Zed\Maintenance\Communication\MaintenanceDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method MaintenanceFacade getFacade()
 * * @method MaintenanceDependencyContainer getDependencyContainer()
 */
class DataController extends AbstractController
{

    public function storageAction(){

        $table = $this->getDependencyContainer()->createStorageTable();
        return $this->viewResponse(['table' => $table->render()]);
    }

    public function searchAction(){

        $table = $this->getDependencyContainer()->createSearchTable();
        return $this->viewResponse(['searchTable' => $table->render()]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createStorageTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
 * @return JsonResponse
 */
    public function searchTableAction()
    {
        $table = $this->getDependencyContainer()->createSearchTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    public function searchKeyAction(Request $request)
    {
        $key = $request->get('key');

        $str = '{"query":{ "ids":{ "values": [ '.$key.' ] } } }';
        $query = $this->getDependencyContainer()->getSearchClient()
            ->getIndexClient()->search(json_decode($str, true));

        $value = $query->getResults();

        return $this->viewResponse([
            'value' => var_export($value, true),
            'key' => $key
        ]);
    }


    public function storageKeyAction(Request $request){
        $key = $request->get('key');
        $value = $this->getDependencyContainer()->getStorageClient()->get($key);

        $link = '';

        if(is_array($value)){
            foreach($value as $k => $v){
                if($k === 'reference_key'){
                   $link = '<a href="/maintenance/index/storage-key?key='.$v.'">'.$v.'</a>';
                }
            }
        }

        return $this->viewResponse([
            'value' => var_export($value, true),
            'key' => $key,
            'link' => $link

        ]);
    }

}
