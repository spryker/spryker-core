<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOptionExporter\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\ProductOptionExporter\Communication\ProductOptionExporterDependencyContainer;

/**
 * @method ProductOptionExporterDependencyContainer getDependencyContainer()
 */
class ProductOptionExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{

    /**
     * @return string
     */
    public function getProcessableType()
    {
        return 'abstract_product';
    }

    /**
     * @param ModelCriteria $expandableQuery
     * @param LocaleTransfer $locale
     *
     * @return ModelCriteria
     */
    public function expandQuery(ModelCriteria $expandableQuery, LocaleTransfer $locale)
    {
        return $expandableQuery;
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }

}
