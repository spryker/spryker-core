<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Payolution\Communication\PayolutionDependencyContainer;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method PayolutionDependencyContainer getDependencyContainer()
 * @method PayolutionQueryContainerInterface getQueryContainer()
 */
class DetailsController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idPayment = (int) $request->get('id-payment');
        $paymentEntity = $this->getPaymentEntity($idPayment);
        $requestLogTable = $this->getDependencyContainer()->createRequestLogTable($idPayment);
        $statusLogTable = $this->getDependencyContainer()->createStatusLogTable($idPayment);

        return [
            'idPayment' => $idPayment,
            'paymentDetails' => $paymentEntity,
            'requestLogTable' => $requestLogTable->render(),
            'statusLogTable' => $statusLogTable->render(),
        ];
    }

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolution
     */
    private function getPaymentEntity($idPayment)
    {
        $paymentEntity = $this->getQueryContainer()->queryPaymentById($idPayment)->findOne();

        if (null === $paymentEntity) {
            throw new NotFoundHttpException('Payment entity could not be found');
        }

        return $paymentEntity;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function requestLogTableAction(Request $request)
    {
        $idPayment = (int) $request->get('id-payment');
        $requestLogTable = $this->getDependencyContainer()->createRequestLogTable($idPayment);

        return $this->jsonResponse($requestLogTable->fetchData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function statusLogTableAction(Request $request)
    {
        $idPayment = (int) $request->get('id-payment');
        $statusLogTable = $this->getDependencyContainer()->createStatusLogTable($idPayment);

        return $this->jsonResponse($statusLogTable->fetchData());
    }

}
