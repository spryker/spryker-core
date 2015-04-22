<?php

namespace SprykerFeature\Zed\FrontendExporter\Dependency\Plugin;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface QueryExpanderPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType();

    /**
     * @param ModelCriteria $expandableQuery
     * @param string $localeName
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $localeName);

    /**
     * @return int
     */
    public function getChunkSize();
}
