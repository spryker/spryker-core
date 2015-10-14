<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Payment\MethodMapper;

use SprykerFeature\Shared\Payolution\PayolutionApiConstants;

class Invoice extends AbstractMethodMapper
{

    /**
     * @return string
     */
    public function getAccountBrand()
    {
        return PayolutionApiConstants::BRAND_INVOICE;
    }

}
