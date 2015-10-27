<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Discount\Business\Model;

interface VoucherCodeInterface
{

    /**
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function releaseUsedCodes(array $codes);

    /**
     * @param array|string[] $codes
     *
     * @return bool
     */
    public function useCodes(array $codes);

}
