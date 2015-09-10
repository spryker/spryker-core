<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Payment;

use Generated\Shared\Transfer\OrderListTransfer;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Response\PreAuthorizationResponse;
use SprykerFeature\Zed\Payolution\Persistence\PayolutionQueryContainerInterface;
use SprykerFeature\Zed\Sales\Business\SalesFacade;
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
        $this->executionAdapter = $executionAdapter;
        $this->queryContainer = $queryContainer;
    }


    /**
     * @param MethodMapperInterface $mapper
     */
    public function registerMethodMapper(MethodMapperInterface $mapper)
    {
        $this->methodMappers[$mapper->getName()] = $mapper;
    }

    /**
     * @param int $idPayment
     *
     * @return PreAuthorizationResponse
     */
    public function preAuthorizePayment($idPayment)
    {
        $paymentEntity = $this->queryContainer->queryPaymentById($idPayment);

        $authorizationRequest = $this
            ->methodMappers[MethodMapperInterface::INVOICE]
            ->mapToPreAuthorization($paymentEntity);

        $authorizationResponse = $this->executionAdapter->sendRequest($authorizationRequest);

        $response = new PreAuthorizationResponse();
        $response->initFromArray($authorizationResponse);

        return $response;
    }

}
