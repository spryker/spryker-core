<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxProductConnector\Persistence;

use Orm\Zed\Product\Persistence\SpyAbstractProductQuery;

interface TaxProductConnectorQueryContainerInterface
{

    /**
     * @param int $idTaxRate
     *
     * @return SpyAbstractProductQuery
     */
    public function getAbstractProductIdsForTaxRate($idTaxRate);

    /**
     * @param int $idTaxSet
     *
     * @return SpyAbstractProductQuery
     */
    public function getAbstractProductIdsForTaxSet($idTaxSet);

}
