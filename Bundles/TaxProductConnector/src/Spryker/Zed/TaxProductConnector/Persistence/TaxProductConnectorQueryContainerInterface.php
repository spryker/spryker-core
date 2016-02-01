<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector\Persistence;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;

interface TaxProductConnectorQueryContainerInterface
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
