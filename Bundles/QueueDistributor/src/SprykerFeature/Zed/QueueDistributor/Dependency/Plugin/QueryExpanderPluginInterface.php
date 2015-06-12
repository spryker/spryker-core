<?php

namespace SprykerFeature\Zed\QueueDistributor\Dependency\Plugin;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface QueryExpanderPluginInterface
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
