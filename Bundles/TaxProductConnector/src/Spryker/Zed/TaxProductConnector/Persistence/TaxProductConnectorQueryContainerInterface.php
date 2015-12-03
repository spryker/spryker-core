<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface TaxProductConnectorQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param int $idTaxRate
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getAbstractAbstractIdsForTaxRate($idTaxRate);

    /**
     * @param int $idTaxSet
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractIdsForTaxSet($idTaxSet);

}
