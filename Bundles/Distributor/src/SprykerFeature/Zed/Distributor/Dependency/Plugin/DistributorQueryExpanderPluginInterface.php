<?php

namespace SprykerFeature\Zed\Distributor\Dependency\Plugin;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface DistributorQueryExpanderPluginInterface
{

    /**
     * @return string
     */
    public function getType();

    /**
     * @param ModelCriteria $expandableQuery
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery);

    /**
     * @return int
     */
    public function getChunkSize();

}
