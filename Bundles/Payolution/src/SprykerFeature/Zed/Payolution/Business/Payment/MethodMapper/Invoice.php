<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Header;
use SprykerFeature\Zed\Payolution\Business\Api\Request\Partial\Transaction;
use SprykerFeature\Zed\Payolution\Business\Api\Request\PreAuthorizationRequest;
use SprykerFeature\Zed\Payolution\Business\Payment\MethodMapperInterface;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

class Invoice extends AbstractMethodMapper
{

    /**
     * @return string
     */
    public function getName()
    {
        return MethodMapperInterface::INVOICE;
    }

    /**
     * @param SpySalesOrder $salesOrder
     *
     * @return PreAuthorizationRequest
     */
    public function mapToPreAuthorization(SpySalesOrder $salesOrder)
    {
        $request = new PreAuthorizationRequest();
        $request->setHeader($this->getHeaderPartialRequest());
        $request->setTransaction($this->getTransactionPartialRequest());
        return $request;
    }

    /**
     * @return string
     */
    protected function getChannel()
    {
        return $this->getConfig()->getChannelInvoice();
    }

}
