<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Business;

use Exception;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSetStorage\Business\Expander\ProductSetDataStorageExpander;
use Spryker\Zed\ProductSetStorage\Business\Expander\ProductSetDataStorageExpanderInterface;
use Spryker\Zed\ProductSetStorage\Business\Reader\GlossaryReader;
use Spryker\Zed\ProductSetStorage\Business\Reader\GlossaryReaderInterface;
use Spryker\Zed\ProductSetStorage\Business\Storage\ProductSetStorageWriter;
use Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToGlossaryFacadeInterface;
use Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToProductImageFacadeInterface;
use Spryker\Zed\ProductSetStorage\ProductSetStorageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSetStorage\ProductSetStorageConfig getConfig()
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageRepositoryInterface getRepository()
 */
class ProductSetStorageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductSetStorage\Business\Storage\ProductSetStorageWriterInterface
     */
    public function createProductSetStorageWriter()
    {
        return new ProductSetStorageWriter(
            $this->getQueryContainer(),
            $this->getConfig()->isSendingToQueue(),
            $this->createProductSetDataStorageExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductSetStorage\Business\Expander\ProductSetDataStorageExpanderInterface|null
     */
    public function createProductSetDataStorageExpander(): ?ProductSetDataStorageExpanderInterface
    {
        if (!$this->getProductImageFacade()->isProductImageAlternativeTextEnabled()) {
            return null;
        }

        return new ProductSetDataStorageExpander(
            $this->createGlossaryReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductSetStorage\Business\Reader\GlossaryReaderInterface|null
     */
    public function createGlossaryReader(): ?GlossaryReaderInterface
    {
        if (!$this->getProductImageFacade()->isProductImageAlternativeTextEnabled()) {
            return null;
        }

        return new GlossaryReader(
            $this->getGlossaryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): ProductSetStorageToGlossaryFacadeInterface
    {
        $this->assertProductImageAlternativeTextEnabled();

        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductSetStorage\Dependency\Facade\ProductSetStorageToProductImageFacadeInterface
     */
    public function getProductImageFacade(): ProductSetStorageToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ProductSetStorageDependencyProvider::FACADE_PRODUCT_IMAGE);
    }

    /**
     * @deprecated This method will be removed in the next major version.
     * The product image alternative text feature will be enabled by default and the dependency will be mandatory.
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function assertProductImageAlternativeTextEnabled(): void
    {
        if (!$this->getProductImageFacade()->isProductImageAlternativeTextEnabled()) {
            throw new Exception('ProductImageAlternativeText is not enabled. Enable it in the module config first.');
        }
    }
}
