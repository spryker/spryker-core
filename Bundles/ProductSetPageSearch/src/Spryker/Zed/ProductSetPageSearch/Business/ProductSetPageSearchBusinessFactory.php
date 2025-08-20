<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Business;

use Exception;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapper;
use Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapperInterface;
use Spryker\Zed\ProductSetPageSearch\Business\Expander\ProductSetPageSearchExpander;
use Spryker\Zed\ProductSetPageSearch\Business\Expander\ProductSetPageSearchExpanderInterface;
use Spryker\Zed\ProductSetPageSearch\Business\Reader\GlossaryReader;
use Spryker\Zed\ProductSetPageSearch\Business\Reader\GlossaryReaderInterface;
use Spryker\Zed\ProductSetPageSearch\Business\Search\ProductSetPageSearchWriter;
use Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToGlossaryFacadeInterface;
use Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductImageFacadeInterface;
use Spryker\Zed\ProductSetPageSearch\ProductSetPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductSetPageSearch\ProductSetPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductSetPageSearch\Persistence\ProductSetPageSearchQueryContainerInterface getQueryContainer()
 */
class ProductSetPageSearchBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Business\Search\ProductSetPageSearchWriterInterface
     */
    public function createProductSetPageSearchWriter()
    {
        return new ProductSetPageSearchWriter(
            $this->getQueryContainer(),
            $this->getUtilEncoding(),
            $this->createProductSetSearchDataMapper(),
            $this->getProductSetFacade(),
            $this->getConfig()->isSendingToQueue(),
            $this->createProductSetPageSearchExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Service\ProductSetPageSearchToUtilEncodingInterface
     */
    public function getUtilEncoding()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductSetInterface
     */
    public function getProductSetFacade()
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::FACADE_PRODUCT_SET);
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Business\DataMapper\ProductSetSearchDataMapperInterface
     */
    public function createProductSetSearchDataMapper(): ProductSetSearchDataMapperInterface
    {
        return new ProductSetSearchDataMapper();
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Business\Expander\ProductSetPageSearchExpanderInterface|null
     */
    public function createProductSetPageSearchExpander(): ?ProductSetPageSearchExpanderInterface
    {
        if (!$this->getProductImageFacade()->isProductImageAlternativeTextEnabled()) {
            return null;
        }

        return new ProductSetPageSearchExpander(
            $this->createGlossaryReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Business\Reader\GlossaryReaderInterface|null
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
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): ProductSetPageSearchToGlossaryFacadeInterface
    {
        $this->assertProductImageAlternativeTextEnabled();

        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductImageFacadeInterface
     */
    public function getProductImageFacade(): ProductSetPageSearchToProductImageFacadeInterface
    {
        return $this->getProvidedDependency(ProductSetPageSearchDependencyProvider::FACADE_PRODUCT_IMAGE);
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
            throw new Exception('ProductImageAlternativeText is not enabled. Enable it in the ProductImage module config first.');
        }
    }
}
