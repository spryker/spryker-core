<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Request\PreAuthorizationRequest;
use SprykerFeature\Zed\Payolution\Business\Api\Response\PreAuthorizationResponse;
use SprykerFeature\Zed\Payolution\Business\Payment\EntityToRequestMapper\EntityToRequestMapperInterface;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

class PaymentManager implements PaymentManagerInterface
{

    /**
     * @var AdapterInterface
     */
    protected $executionAdapter;

    /**
     * @var PayolutionQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @var array
     */
    private $methodMappers = [];

    public function __construct(
        AdapterInterface $executionAdapter,
        PayolutionQueryContainerInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
        $this->executionAdapter = $executionAdapter;
    }


    /**
     * @param MethodMapperInterface $mapper
     */
    public function registerMethodMapper(MethodMapperInterface $mapper)
    {
        $this->methodMappers[$mapper->getName()] = $mapper;
    }

    /**
     * @param int $idOrder
     *
     * @return PreAuthorizationResponse
     */
    public function preAuthorizePaymentFromOrder($idOrder)
    {
        $orderEntity = $this->getOrderEntity($idOrder);

        $authorizationRequest = $this
            ->methodMappers[MethodMapperInterface::INVOICE]
            ->mapToPreAuthorization($orderEntity);

        $authorizationResponse = $this->executionAdapter->sendRequest($authorizationRequest);

        return $authorizationResponse;
    }

    /**
     * @param $idOrder
     *
     * @return SpySalesOrder
     */
    private function getOrderEntity($idOrder)
    {
        return $this->queryContainer->querySalesOrderById($idOrder)->findOne();
    }

}
