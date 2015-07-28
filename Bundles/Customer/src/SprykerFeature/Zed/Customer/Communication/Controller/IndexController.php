<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Customer\Communication\CustomerDependencyContainer;
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
        $table = $this->getDependencyContainer()
            ->createCustomerTable()
        ;

        return $this->viewResponse([
            'customerTable' => $table->render(),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()
            ->createCustomerTable()
        ;

        return $this->jsonResponse($table->fetchData());
    }

}
