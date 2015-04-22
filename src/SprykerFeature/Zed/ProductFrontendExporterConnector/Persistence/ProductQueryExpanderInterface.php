<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface ProductQueryExpanderInterface
{
    /**
     * @param ModelCriteria $expandableQuery
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $localeName);
}
