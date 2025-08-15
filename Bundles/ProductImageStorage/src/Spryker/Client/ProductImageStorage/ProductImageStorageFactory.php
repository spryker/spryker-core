<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage;

use Exception;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToGlossaryStorageClientInterface;
use Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToProductImageClientInterface;
use Spryker\Client\ProductImageStorage\Expander\ProductImageStorageExpander;
use Spryker\Client\ProductImageStorage\Expander\ProductImageStorageExpanderInterface;
use Spryker\Client\ProductImageStorage\Expander\ProductViewImageExpander;
use Spryker\Client\ProductImageStorage\Resolver\ProductConcreteImageInheritanceResolver;
use Spryker\Client\ProductImageStorage\Resolver\ProductConcreteImageInheritanceResolverInterface;
use Spryker\Client\ProductImageStorage\Storage\ProductAbstractImageStorageReader;
use Spryker\Client\ProductImageStorage\Storage\ProductConcreteImageStorageReader;
use Spryker\Client\ProductImageStorage\Storage\ProductImageStorageKeyGenerator;

/**
 * @method \Spryker\Client\ProductImageStorage\ProductImageStorageConfig getConfig()
 */
class ProductImageStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductImageStorage\Expander\ProductViewImageExpanderInterface
     */
    public function createProductViewImageExpander()
    {
        return new ProductViewImageExpander(
            $this->createProductAbstractImageStorageReader(),
            $this->createProductConcreteImageInheritanceResolver(),
        );
    }

    /**
     * @return \Spryker\Client\ProductImageStorage\Storage\ProductAbstractImageStorageReaderInterface
     */
    public function createProductAbstractImageStorageReader()
    {
        return new ProductAbstractImageStorageReader(
            $this->getStorage(),
            $this->createProductImageStorageKeyGenerator(),
            $this->createProductImageStorageExpander(),
        );
    }

    /**
     * @return \Spryker\Client\ProductImageStorage\Storage\ProductConcreteImageStorageReaderInterface
     */
    public function createProductConcreteImageStorageReader()
    {
        return new ProductConcreteImageStorageReader(
            $this->getStorage(),
            $this->createProductImageStorageKeyGenerator(),
            $this->createProductImageStorageExpander(),
        );
    }

    /**
     * @return \Spryker\Client\ProductImageStorage\Storage\ProductImageStorageKeyGeneratorInterface
     */
    public function createProductImageStorageKeyGenerator()
    {
        return new ProductImageStorageKeyGenerator($this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\ProductImageStorage\Resolver\ProductConcreteImageInheritanceResolverInterface
     */
    public function createProductConcreteImageInheritanceResolver(): ProductConcreteImageInheritanceResolverInterface
    {
        return new ProductConcreteImageInheritanceResolver(
            $this->createProductConcreteImageStorageReader(),
            $this->createProductAbstractImageStorageReader(),
        );
    }

    /**
     * @return \Spryker\Client\ProductImageStorage\Expander\ProductImageStorageExpanderInterface|null
     */
    public function createProductImageStorageExpander(): ?ProductImageStorageExpanderInterface
    {
        if (!$this->getProductImageClient()->isProductImageAlternativeTextEnabled()) {
            return null;
        }

        return new ProductImageStorageExpander(
            $this->getGlossaryStorageClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToStorageInterface
     */
    public function getStorage()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductImageStorage\Dependency\Service\ProductImageStorageToSynchronizationServiceBridge
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ProductImageStorageToGlossaryStorageClientInterface
    {
        $this->assertProductImageAlternativeTextEnabled();

        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToProductImageClientInterface
     */
    public function getProductImageClient(): ProductImageStorageToProductImageClientInterface
    {
        return $this->getProvidedDependency(ProductImageStorageDependencyProvider::CLIENT_PRODUCT_IMAGE);
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
        if (!$this->getProductImageClient()->isProductImageAlternativeTextEnabled()) {
            throw new Exception('ProductImageAlternativeText is not enabled. Enable it in the ProductImage module shared config first.');
        }
    }
}
