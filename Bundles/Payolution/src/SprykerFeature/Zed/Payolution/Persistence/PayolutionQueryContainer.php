<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\PayolutionPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolutionQuery;

/**
 * @method PayolutionPersistence getFactory()
 */
class PayolutionQueryContainer extends AbstractQueryContainer implements PayolutionQueryContainerInterface
{

    /**
     * @param int $idPayment
     *
     * @return SpyPaymentPayolution
     */
    public function queryPaymentById($idPayment)
    {
        $query = SpyPaymentPayolutionQuery::create();

        return $query->requireOneByIdPaymentPayolution($idPayment);
    }

}
