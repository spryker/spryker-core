<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Discount\Business\Model;

interface VoucherCodeInterface
{
    /**
     * @param array $codes
     *
     * @return bool
     */
    public function enableCodes(array $codes);
}
