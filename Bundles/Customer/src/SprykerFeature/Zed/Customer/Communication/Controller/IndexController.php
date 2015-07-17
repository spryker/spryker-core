<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;
use SprykerFeature\Zed\Customer\Communication\Table\CustomerTable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method CustomerDependencyContainer getDependencyContainer
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        /** @var CustomerTable $table */
        $table = $this->getDependencyContainer()->createCustomerTable();
        $table->init();

        return $this->viewResponse([
            'customerTable' => $table,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        /** @var CustomerTable $table */
        $table = $this->getDependencyContainer()->createCustomerTable();
        $table->init();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
