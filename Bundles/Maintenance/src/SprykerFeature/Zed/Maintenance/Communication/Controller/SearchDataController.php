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
 * @method MaintenanceDependencyContainer getDependencyContainer()
 */
class SearchDataController extends AbstractController
{

    /**
     * @return array
     */
    public function searchAction()
    {
        $table = $this->getDependencyContainer()->createSearchTable();

        return $this->viewResponse(['searchTable' => $table->render()]);
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

    /**
     * @param Request $request
     *
     * @return array
     */
    public function searchKeyAction(Request $request)
    {
        $key = $request->get('key');

        $queryString = '{"query":{ "ids":{ "values": [ ' . $key . ' ] } } }';
        $searchQuery = $this->getDependencyContainer()->getSearchClient()
            ->getIndexClient()->search(json_decode($queryString, true));

        $searchResult = $searchQuery->getResults();

        return $this->viewResponse([
            'value' => var_export($searchResult, true),
            'key' => $key,
        ]);
    }

}
