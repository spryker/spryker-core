<?php

namespace Spryker\Zed\Search\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Search\Communication\SearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\Search\Business\SearchFacade getFacade()
 */
class FiltersController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createFiltersTable();

        return [
            'filtersTable' => $table->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createFiltersTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }
}