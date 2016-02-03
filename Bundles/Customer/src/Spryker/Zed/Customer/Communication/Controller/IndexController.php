<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\Customer\Business\CustomerFacade getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{

    public function testAction()
    {
        return $this->viewResponse();
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()
            ->createCustomerTable();

        return $this->viewResponse([
            'customerTable' => $table->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createCustomerTable();

        return $this->jsonResponse($table->fetchData());
    }

}
