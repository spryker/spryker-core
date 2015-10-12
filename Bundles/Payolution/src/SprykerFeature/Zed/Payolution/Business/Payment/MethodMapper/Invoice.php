<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use Generated\Shared\Payolution\PayolutionRequestInterface;
use SprykerFeature\Zed\Payolution\Business\Api\Constants;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

class Invoice extends AbstractMethodMapper
{

    /**
     * @return string
     */
    public function getAccountBrand()
    {
        return Constants::ACCOUNT_BRAND_INVOICE;
    }

}
