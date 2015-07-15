<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterConnector\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method ProductFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class ProductFrontendExporterConnectorQueryContainer extends AbstractQueryContainer implements ProductFrontendExporterConnectorQueryContainerInterface
{

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        return $this->getDependencyContainer()->getProductQueryExpander()->expandQuery($expandableQuery, $locale);
    }

}
