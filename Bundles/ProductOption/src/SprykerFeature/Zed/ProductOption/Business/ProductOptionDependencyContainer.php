<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductOption\Business;

use SprykerFeature\Zed\ProductOption\Business\Model\ProductOptionReader;
use SprykerFeature\Zed\ProductOption\Business\Model\DataImportWriter;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\ProductOption\ProductOptionDependencyProvider;
use SprykerFeature\Zed\ProductOption\ProductOptionConfig;
use Generated\Zed\Ide\FactoryAutoCompletion\ProductOptionBusiness;
use SprykerFeature\Zed\ProductOption\Business\Model\DataImportWriterInterface;
use SprykerFeature\Zed\ProductOption\Business\Model\ProductOptionReaderInterface;

/**
 * @method ProductOptionConfig getConfig()
 */
class ProductOptionDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return DataImportWriterInterface
     */
    public function getDataImportWriterModel()
    {
        return new DataImportWriter(
            $this->getQueryContainer(),
            $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_PRODUCT),
            $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_LOCALE)
        );
    }

    /**
     * @return ProductOptionReaderInterface
     */
    public function getProductOptionReaderModel()
    {
        return new ProductOptionReader(
            $this->getQueryContainer(),
            $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_LOCALE)
        );
    }

}
