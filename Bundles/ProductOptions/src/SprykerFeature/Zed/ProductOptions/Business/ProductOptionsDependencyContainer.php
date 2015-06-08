<?php

namespace SprykerFeature\Zed\ProductOptions\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\ProductOptions\ProductOptionsConfig;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductOptionsBusiness;
use SprykerFeature\Zed\ProductOptions\Business\Model\DataImportWriterInterface;
use SprykerFeature\Zed\ProductOptions\Dependency\Facade\ProductOptionsToProductInterface;

/**
 * @method ProductOptionsBusiness getFactory()
 * @method ProductOptionsConfig getConfig()
 */
class ProductOptionsDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return DataImportWriterInterface
     */
    public function getDataImportWriterModel()
    {
        return $this->getFactory()->createModelDataImportWriter(
            $this->getLocator()->productOptions()->queryContainer(),
            $this->createProductFacade()
        );
    }

    /**
     * @return ProductOptionsToProductInterface
     */
    protected function createProductFacade()
    {
        return $this->getLocator()->product()->facade();
    }
}
