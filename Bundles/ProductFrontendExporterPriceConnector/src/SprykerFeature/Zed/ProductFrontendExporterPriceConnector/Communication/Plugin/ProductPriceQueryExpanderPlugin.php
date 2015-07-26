<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Collector\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Communication\ProductFrontendExporterPriceConnectorDependencyContainer;

/**
 * @method ProductFrontendExporterPriceConnectorDependencyContainer getDependencyContainer()
 */
class ProductPriceQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
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
        $priceTypeEntity = $this->getDependencyContainer()->getQueryContainer()->getFkDefaultPriceType($this->getDefaultPriceType())->findOne();

        return $this->getDependencyContainer()->getQueryContainer()->expandQuery($expandableQuery, $priceTypeEntity->getIdPriceType());
    }

    /**
     * @return string
     */
    protected function getDefaultPriceType()
    {
        return $this->getDependencyContainer()->getPriceProcessor()->getDefaultPriceType();
    }
    /**
     * @return int
     */
    public function getChunkSize()
    {
        return 100;
    }

}
