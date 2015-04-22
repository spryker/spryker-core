<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface ProductFrontendExporterConnectorQueryContainerInterface
{
    /**
     * @param ModelCriteria $expandableQuery
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $localeName);
}
