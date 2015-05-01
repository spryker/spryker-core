<?php

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Persistence;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

/**
 * @method ProductFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductFrontendExporterConnectorQueryContainer extends AbstractQueryContainer implements ProductFrontendExporterConnectorQueryContainerInterface
{
    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleDto $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleDto $locale)
    {
        return $this->getDependencyContainer()->getProductQueryExpander()->expandQuery($expandableQuery, $locale);
    }
}
