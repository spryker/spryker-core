<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Communication\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\FrontendExporter\Dependency\Plugin\QueryExpanderPluginInterface;
use SprykerFeature\Zed\TaxFrontendExporterConnector\Communication\TaxFrontendExporterConnectorDependencyContainer;

/**
 * @method TaxFrontendExporterConnectorDependencyContainer getDependencyContainer()
 */
class TaxQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
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
        return $this->getDependencyContainer()->getQueryContainer()->expandQuery($expandableQuery);
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
