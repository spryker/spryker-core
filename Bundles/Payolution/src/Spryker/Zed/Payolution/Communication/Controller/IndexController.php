<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Zed\Payolution\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Payolution\Communication\PayolutionDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method PayolutionDependencyContainer getCommunicationFactory()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getCommunicationFactory()->createPaymentsTable();

        return [
            'payments' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getCommunicationFactory()->createPaymentsTable();

        return $this->jsonResponse($table->fetchData());
    }

}
