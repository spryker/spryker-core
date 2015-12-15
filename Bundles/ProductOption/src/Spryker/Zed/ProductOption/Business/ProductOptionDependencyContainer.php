<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Business;

use Spryker\Zed\ProductOption\Business\Model\ProductOptionReader;
use Spryker\Zed\ProductOption\Business\Model\DataImportWriter;
use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\ProductOption\ProductOptionDependencyProvider;
use Spryker\Zed\ProductOption\ProductOptionConfig;
use Spryker\Zed\ProductOption\Business\Model\DataImportWriterInterface;
use Spryker\Zed\ProductOption\Business\Model\ProductOptionReaderInterface;

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
