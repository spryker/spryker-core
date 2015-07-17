<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Business\Model\ExportProcessor;
use SprykerFeature\Zed\ProductFrontendExporterPriceConnector\Business\Model\HelperInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductFrontendExporterPriceConnectorBusiness;

/**
 * @method ProductFrontendExporterPriceConnectorBusiness getFactory()
 */
class ProductFrontendExporterPriceConnectorDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @var ProductFrontendExporterPriceConnectorFacade
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
