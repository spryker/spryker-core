<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface ProductPriceExpanderInterface
{

    /**
     * @param ModelCriteria $expandableQuery
     * @param int $idPriceType
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $idPriceType);

}
