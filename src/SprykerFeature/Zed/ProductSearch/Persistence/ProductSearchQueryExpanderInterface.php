<?php

namespace SprykerFeature\Zed\ProductSearch\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface ProductSearchQueryExpanderInterface
{
    /**
     * @param ModelCriteria $expandableQuery
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandProductQuery(ModelCriteria $expandableQuery, $localeName);
}
