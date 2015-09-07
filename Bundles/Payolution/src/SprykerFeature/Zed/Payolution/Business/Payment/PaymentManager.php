<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;



use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use \SprykerFeature\Zed\Payolution\Business\Api\Request\PreAuthorizationRequest;
use SprykerFeature\Zed\Payolution\Business\Api\Response\PreAuthorizationResponse;
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

    public function __construct(
        AdapterInterface $executionAdapter,
        PayolutionQueryContainerInterface $queryContainer
    ) {
        $this->queryContainer = $queryContainer;
        $this->executionAdapter = $executionAdapter;
    }

    /**
     * @param int $idOrder
     *
     * @return PreAuthorizationResponse
     */
    public function preAuthorizePaymentFromOrder($idOrder)
    {
        $orderEntity = $this->getOrderEntity($idOrder);

        $authorizationRequest = new PreAuthorizationRequest();
        $authorizationRequest = $this->mapSalesOrderToAbstractAuthorization($authorizationRequest, $orderEntity);

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
