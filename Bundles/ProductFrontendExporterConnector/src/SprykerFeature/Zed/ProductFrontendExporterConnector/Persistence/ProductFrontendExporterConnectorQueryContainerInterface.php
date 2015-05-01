<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Shared\Dto\LocaleDto;

interface ProductFrontendExporterConnectorQueryContainerInterface
{
    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleDto $locale);
}
