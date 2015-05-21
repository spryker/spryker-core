<?php

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\TaxFrontendExporterConnector\Business\Model\ExportProcessor;
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
     * @return ExportProcessor
     */
    public function getProcessorModel()
    {
        return $this->getFactory()->createModelExportProcessor(
            $this->getHelperModel()
        );
    }

    /**
     * @return HelperInterface
     */
    public function getHelperModel()
    {
        return $this->getFactory()->createModelHelper(
            $this->getPriceFacade()
        );
    }

    /**
     * @return PriceFacade
     */
    public function getPriceFacade()
    {
        return $this->getLocator()->price()->facade();
    }
}
