<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request\Partial;


use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequest;

class Account extends AbstractRequest
{
    const BRAND_INVOICE = 'PAYOLUTION_INVOICE';
    const BRAND_INSTALLMENT = 'PAYOLUTION_INS';

    /**
     * @var string
     */
    protected $brand;

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param string $brand
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    }


}
