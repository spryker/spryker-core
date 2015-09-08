<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use SprykerFeature\Zed\Payolution\Business\Api\Request\PreAuthorizationRequest;
use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapperInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

class Installment extends AbstractMethodMapper
{

    /**
     * @return string
     */
    public function getName()
    {
        return MethodMapperInterface::INSTALLMENT;
    }

    /**
     * @param SpySalesOrder $salesOrder
     *
     * @return PreAuthorizationRequest
     */
    public function mapToPreAuthorization(SpySalesOrder $salesOrder)
    {
        $request = new PreAuthorizationRequest();
        return $request;
    }

    /**
     * @return string
     */
    protected function getChannel()
    {
        return $this->getConfig()->getChannelInstallment();
    }

}
