<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Business;

use Spryker\Zed\ProductOption\Business\Model\ProductOptionReader;
use Spryker\Zed\ProductOption\Business\Model\DataImportWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;
use Spryker\Zed\ProductOption\ProductOptionConfig;
use Spryker\Zed\ProductOption\Business\Model\DataImportWriterInterface;
use Spryker\Zed\ProductOption\Business\Model\ProductOptionReaderInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer;

/**
 * @method ProductOptionConfig getConfig()
 * @method ProductOptionQueryContainer getQueryContainer()
 */
class ProductOptionBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return DataImportWriterInterface
     */
    public function createDataImportWriterModel()
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
    public function createProductOptionReaderModel()
    {
        return new ProductOptionReader(
            $this->getQueryContainer(),
            $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_LOCALE)
        );
    }

}
