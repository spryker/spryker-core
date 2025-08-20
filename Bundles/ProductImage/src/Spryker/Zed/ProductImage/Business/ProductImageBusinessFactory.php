<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business;

use Exception;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductImage\Business\Expander\ProductConcretePageSearchExpander;
use Spryker\Zed\ProductImage\Business\Expander\ProductConcretePageSearchExpanderInterface;
use Spryker\Zed\ProductImage\Business\Expander\ProductImageSetExpander;
use Spryker\Zed\ProductImage\Business\Expander\ProductImageSetExpanderInterface;
use Spryker\Zed\ProductImage\Business\Expander\ProductPageSearchExpander;
use Spryker\Zed\ProductImage\Business\Expander\ProductPageSearchExpanderInterface;
use Spryker\Zed\ProductImage\Business\Model\ProductImageSetCombiner;
use Spryker\Zed\ProductImage\Business\Model\Reader;
use Spryker\Zed\ProductImage\Business\Model\Writer;
use Spryker\Zed\ProductImage\Business\Reader\GlossaryReader;
use Spryker\Zed\ProductImage\Business\Reader\GlossaryReaderInterface;
use Spryker\Zed\ProductImage\Business\Reader\ProductImageBulkReader;
use Spryker\Zed\ProductImage\Business\Reader\ProductImageBulkReaderInterface;
use Spryker\Zed\ProductImage\Business\Reader\ProductImageSetReader;
use Spryker\Zed\ProductImage\Business\Reader\ProductImageSetReaderInterface;
use Spryker\Zed\ProductImage\Business\Resolver\ProductImageSetResolver;
use Spryker\Zed\ProductImage\Business\Resolver\ProductImageSetResolverInterface;
use Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapper;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToGlossaryFacadeInterface;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToStoreFacadeInterface;
use Spryker\Zed\ProductImage\ProductImageDependencyProvider;

/**
 * @method \Spryker\Zed\ProductImage\ProductImageConfig getConfig()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface getRepository()
 */
class ProductImageBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductImage\Business\Model\ReaderInterface
     */
    public function createProductImageReader()
    {
        return new Reader(
            $this->getQueryContainer(),
            $this->createTransferGenerator(),
            $this->getLocaleFacade(),
            $this->getRepository(),
            $this->createProductImageSetExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Reader\ProductImageBulkReaderInterface
     */
    public function createProductImageBulkReader(): ProductImageBulkReaderInterface
    {
        return new ProductImageBulkReader($this->getRepository(), $this->getLocaleFacade());
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Model\WriterInterface
     */
    public function createProductImageWriter()
    {
        return new Writer(
            $this->getQueryContainer(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Transfer\ProductImageTransferMapperInterface
     */
    public function createTransferGenerator()
    {
        return new ProductImageTransferMapper(
            $this->getLocaleFacade(),
            $this->createProductImageSetExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Model\ProductImageSetCombinerInterface
     */
    public function createProductImageSetCombiner()
    {
        return new ProductImageSetCombiner(
            $this->getQueryContainer(),
            $this->createTransferGenerator(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Resolver\ProductImageSetResolverInterface
     */
    public function createProductImageSetResolver(): ProductImageSetResolverInterface
    {
        return new ProductImageSetResolver();
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Reader\ProductImageSetReaderInterface
     */
    public function createProductImageSetReader(): ProductImageSetReaderInterface
    {
        return new ProductImageSetReader(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Expander\ProductImageSetExpanderInterface|null
     */
    public function createProductImageSetExpander(): ?ProductImageSetExpanderInterface
    {
        if (!$this->getConfig()->isProductImageAlternativeTextEnabled()) {
            return null;
        }

        return new ProductImageSetExpander(
            $this->getLocaleFacade(),
            $this->getStoreFacade(),
            $this->createGlossaryReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Reader\GlossaryReaderInterface|null
     */
    public function createGlossaryReader(): ?GlossaryReaderInterface
    {
        if (!$this->getConfig()->isProductImageAlternativeTextEnabled()) {
            return null;
        }

        return new GlossaryReader(
            $this->getGlossaryFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Expander\ProductConcretePageSearchExpanderInterface|null
     */
    public function createProductConcretePageSearchExpander(): ?ProductConcretePageSearchExpanderInterface
    {
        if (!$this->getConfig()->isProductImageAlternativeTextEnabled()) {
            return null;
        }

        return new ProductConcretePageSearchExpander(
            $this->createGlossaryReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Business\Expander\ProductPageSearchExpanderInterface|null
     */
    public function createProductPageSearchExpander(): ?ProductPageSearchExpanderInterface
    {
        if (!$this->getConfig()->isProductImageAlternativeTextEnabled()) {
            return null;
        }

        return new ProductPageSearchExpander(
            $this->createGlossaryReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface
     */
    protected function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductImageDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): ProductImageToGlossaryFacadeInterface
    {
        $this->assertProductImageAlternativeTextEnabled();

        return $this->getProvidedDependency(ProductImageDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToStoreFacadeInterface
     */
    public function getStoreFacade(): ProductImageToStoreFacadeInterface
    {
        $this->assertProductImageAlternativeTextEnabled();

        return $this->getProvidedDependency(ProductImageDependencyProvider::FACADE_STORE);
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
        if (!$this->getConfig()->isProductImageAlternativeTextEnabled()) {
            throw new Exception('ProductImageAlternativeText is not enabled. Enable it in the module shared config first.');
        }
    }
}
