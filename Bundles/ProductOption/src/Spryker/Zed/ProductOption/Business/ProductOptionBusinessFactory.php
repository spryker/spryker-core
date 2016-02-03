<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Business;

use Spryker\Zed\ProductOption\Business\Model\ProductOptionReader;
use Spryker\Zed\ProductOption\Business\Model\DataImportWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;

/**
 * @method \Spryker\Zed\ProductOption\ProductOptionConfig getConfig()
 * @method \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainer getQueryContainer()
 */
class ProductOptionBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\ProductOption\Business\Model\DataImportWriterInterface
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
     * @return \Spryker\Zed\ProductOption\Business\Model\ProductOptionReaderInterface
     */
    public function createProductOptionReaderModel()
    {
        return new ProductOptionReader(
            $this->getQueryContainer(),
            $this->getProvidedDependency(ProductOptionDependencyProvider::FACADE_LOCALE)
        );
    }

}
