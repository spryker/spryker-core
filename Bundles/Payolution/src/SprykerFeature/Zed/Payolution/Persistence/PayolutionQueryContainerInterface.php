<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Persistence;


use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

interface PayolutionQueryContainerInterface
{
    /**
     * @param $idPayment
     *
     * @return SpyPaymentPayolution
     */
    public function queryPaymentById($idPayment);
}
