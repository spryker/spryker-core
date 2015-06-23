<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\TaxFrontendExporterConnector\Business\Model\ExportProcessorInterface;
use SprykerFeature\Zed\TaxFrontendExporterConnector\Business\Model\HelperInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\TaxFrontendExporterConnectorBusiness;

/**
 * @method TaxFrontendExporterConnectorBusiness getFactory()
 */
class TaxFrontendExporterConnectorDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var TaxFrontendExporterConnectorFacade
     */
    protected $facade;

    /**
     * @return ExportProcessorInterface
     */
    public function getProcessorModel()
    {
        return $this->getFactory()->createModelExportProcessor();
    }
}
