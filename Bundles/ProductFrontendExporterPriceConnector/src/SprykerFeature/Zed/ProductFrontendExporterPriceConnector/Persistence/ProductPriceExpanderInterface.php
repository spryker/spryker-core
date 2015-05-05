<?php

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
