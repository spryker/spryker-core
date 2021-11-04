<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationWishlist;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationStorageClientInterface;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface;
use Spryker\Client\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToProductConfigurationServiceInterface;
use Spryker\Client\ProductConfigurationWishlist\Expander\ProductConfigurationPriceExpander;
use Spryker\Client\ProductConfigurationWishlist\Expander\ProductConfigurationPriceExpanderInterface;
use Spryker\Client\ProductConfigurationWishlist\Expander\ProductConfigurationWishlistExpander;
use Spryker\Client\ProductConfigurationWishlist\Expander\ProductConfigurationWishlistExpanderInterface;
use Spryker\Client\ProductConfigurationWishlist\Expander\ProductConfigurationWishlistMoveToCartExpander;
use Spryker\Client\ProductConfigurationWishlist\Expander\ProductConfigurationWishlistMoveToCartExpanderInterface;
use Spryker\Client\ProductConfigurationWishlist\Expander\WishlistItemExpander;
use Spryker\Client\ProductConfigurationWishlist\Expander\WishlistItemExpanderInterface;
use Spryker\Client\ProductConfigurationWishlist\Processor\ProductConfiguratorResponseProcessor;
use Spryker\Client\ProductConfigurationWishlist\Processor\ProductConfiguratorResponseProcessorInterface;
use Spryker\Client\ProductConfigurationWishlist\Resolver\ProductConfiguratorRedirectResolver;
use Spryker\Client\ProductConfigurationWishlist\Resolver\ProductConfiguratorRedirectResolverInterface;
use Spryker\Client\ProductConfigurationWishlist\Validator\ProductConfiguratorResponseValidator;
use Spryker\Client\ProductConfigurationWishlist\Validator\ProductConfiguratorResponseValidatorInterface;

class ProductConfigurationWishlistFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Expander\WishlistItemExpanderInterface
     */
    public function createWishlistItemExpander(): WishlistItemExpanderInterface
    {
        return new WishlistItemExpander(
            $this->getProductConfigurationStorageClient(),
            $this->getWishlistClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Resolver\ProductConfiguratorRedirectResolverInterface
     */
    public function createProductConfiguratorRedirectResolver(): ProductConfiguratorRedirectResolverInterface
    {
        return new ProductConfiguratorRedirectResolver(
            $this->getWishlistClient(),
            $this->getProductConfigurationClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Validator\ProductConfiguratorResponseValidatorInterface
     */
    public function createProductConfiguratorResponseValidator(): ProductConfiguratorResponseValidatorInterface
    {
        return new ProductConfiguratorResponseValidator(
            $this->getProductConfigurationClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Processor\ProductConfiguratorResponseProcessorInterface
     */
    public function createProductConfiguratorResponseProcessor(): ProductConfiguratorResponseProcessorInterface
    {
        return new ProductConfiguratorResponseProcessor(
            $this->getProductConfigurationClient(),
            $this->createProductConfiguratorResponseValidator(),
            $this->getWishlistClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Expander\ProductConfigurationWishlistMoveToCartExpanderInterface
     */
    public function createProductConfigurationWishlistMoveToCartExpander(): ProductConfigurationWishlistMoveToCartExpanderInterface
    {
        return new ProductConfigurationWishlistMoveToCartExpander(
            $this->getProductConfigurationService(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Expander\ProductConfigurationWishlistExpanderInterface
     */
    public function createProductConfigurationWishlistExpander(): ProductConfigurationWishlistExpanderInterface
    {
        return new ProductConfigurationWishlistExpander(
            $this->getProductConfigurationService(),
        );
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Expander\ProductConfigurationPriceExpanderInterface
     */
    public function createProductConfigurationPriceExpander(): ProductConfigurationPriceExpanderInterface
    {
        return new ProductConfigurationPriceExpander();
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationStorageClientInterface
     */
    public function getProductConfigurationStorageClient(): ProductConfigurationWishlistToProductConfigurationStorageClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistDependencyProvider::CLIENT_PRODUCT_CONFIGURATION_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToWishlistClientInterface
     */
    public function getWishlistClient(): ProductConfigurationWishlistToWishlistClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistDependencyProvider::CLIENT_WISHLIST);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Dependency\Client\ProductConfigurationWishlistToProductConfigurationClientInterface
     */
    public function getProductConfigurationClient(): ProductConfigurationWishlistToProductConfigurationClientInterface
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistDependencyProvider::CLIENT_PRODUCT_CONFIGURATION);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationWishlist\Dependency\Service\ProductConfigurationWishlistToProductConfigurationServiceInterface
     */
    public function getProductConfigurationService(): ProductConfigurationWishlistToProductConfigurationServiceInterface
    {
        return $this->getProvidedDependency(ProductConfigurationWishlistDependencyProvider::SERVICE_PRODUCT_CONFIGURATION);
    }
}
