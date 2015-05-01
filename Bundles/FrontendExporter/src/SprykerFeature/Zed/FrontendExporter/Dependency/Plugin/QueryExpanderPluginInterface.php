<?php

namespace SprykerFeature\Zed\FrontendExporter\Dependency\Plugin;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Shared\Dto\LocaleDto;

interface QueryExpanderPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType();

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleDto $locale);

    /**
     * @return int
     */
    public function getChunkSize();
}
