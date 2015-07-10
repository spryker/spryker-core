<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxFrontendExporterConnector\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\TaxFrontendExporterConnector\Business\Model\ExportProcessorInterface;
use Generated\Zed\Ide\FactoryAutoCompletion\TaxFrontendExporterConnectorBusiness;

/**
 * @method TaxFrontendExporterConnectorBusiness getFactory()
 */
class TaxFrontendExporterConnectorDependencyContainer extends AbstractBusinessDependencyContainer
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
