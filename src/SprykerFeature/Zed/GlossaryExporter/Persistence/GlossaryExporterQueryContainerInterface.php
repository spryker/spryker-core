<?php

namespace SprykerFeature\Zed\GlossaryExporter\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface GlossaryExporterQueryContainerInterface
{
    /**
     * @param ModelCriteria $expandableQuery
     * @param string $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, $locale);
}
