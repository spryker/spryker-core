<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Refund\Business;

use Generated\Shared\Payone\PayoneStandardParameterInterface;
use Generated\Shared\Transfer\PayoneTransactionStatusUpdateTransfer;
use Generated\Zed\Ide\FactoryAutoCompletion\RefundBusiness;
use SprykerFeature\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Shared\Payone\PayoneApiConstants;
use SprykerEngine\Zed\Kernel\Business\Factory;
use Generated\Zed\Ide\FactoryAutoCompletion\PayoneBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Payone\Business\Api\TransactionStatus\TransactionStatusRequest;
use SprykerFeature\Zed\Payone\Business\Payment\PaymentManagerInterface;
use SprykerFeature\Zed\Payone\Business\Order\OrderManagerInterface;
use SprykerFeature\Zed\Payone\Business\TransactionStatus\TransactionStatusUpdateManager;
use SprykerFeature\Zed\Payone\PayoneConfig;
use SprykerFeature\Shared\Payone\Dependency\ModeDetectorInterface;
use SprykerFeature\Shared\Payone\Dependency\HashInterface;
use SprykerFeature\Zed\Payone\PayoneDependencyProvider;
use SprykerFeature\Zed\Payone\Persistence\PayoneQueryContainer;
use SprykerFeature\Zed\Payone\Business\SequenceNumber\SequenceNumberProviderInterface;
use SprykerFeature\Zed\Payone\Business\ApiLog\ApiLogFinder;
use SprykerFeature\Zed\Refund\Business\RefundCalculator;
use SprykerFeature\Zed\Refund\RefundConfig;

/**
 * @method Factory|RefundBusiness getFactory()
 * @method RefundConfig getConfig()
 */
class RefundDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return RefundCalculator
     */
    public function getRefundCalculator()
    {
        return $this->getFactory()->createRefundCalculator();
    }

}
