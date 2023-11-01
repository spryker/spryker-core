<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundleCartsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductBundleCartsRestApi\Dependency\Client\ProductBundleCartsRestApiToProductBundleClientInterface;
use Spryker\Glue\ProductBundleCartsRestApi\Dependency\RestResource\ProductBundleCartsRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\ProductBundleCartsRestApi\Processor\Expander\BundledItemExpander;
use Spryker\Glue\ProductBundleCartsRestApi\Processor\Expander\BundledItemExpanderInterface;
use Spryker\Glue\ProductBundleCartsRestApi\Processor\Expander\BundleItemExpander;
use Spryker\Glue\ProductBundleCartsRestApi\Processor\Expander\BundleItemExpanderInterface;
use Spryker\Glue\ProductBundleCartsRestApi\Processor\Expander\QuoteBundleItemExpander;
use Spryker\Glue\ProductBundleCartsRestApi\Processor\Expander\QuoteBundleItemExpanderInterface;
use Spryker\Glue\ProductBundleCartsRestApi\Processor\Filter\BundleItemFilter;
use Spryker\Glue\ProductBundleCartsRestApi\Processor\Filter\BundleItemFilterInterface;
use Spryker\Glue\ProductBundleCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilder;
use Spryker\Glue\ProductBundleCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilderInterface;

class ProductBundleCartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductBundleCartsRestApi\Processor\Expander\BundleItemExpanderInterface
     */
    public function createBundleItemExpander(): BundleItemExpanderInterface
    {
        return new BundleItemExpander(
            $this->getProductBundleClient(),
            $this->getCartsRestApiResource(),
            $this->createBundleItemRestResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductBundleCartsRestApi\Processor\Expander\BundledItemExpanderInterface
     */
    public function createBundledItemExpander(): BundledItemExpanderInterface
    {
        return new BundledItemExpander(
            $this->getProductBundleClient(),
            $this->getCartsRestApiResource(),
            $this->createBundleItemRestResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductBundleCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilderInterface
     */
    public function createBundleItemRestResponseBuilder(): BundleItemRestResponseBuilderInterface
    {
        return new BundleItemRestResponseBuilder($this->getResourceBuilder());
    }

    /**
     * @return \Spryker\Glue\ProductBundleCartsRestApi\Processor\Filter\BundleItemFilterInterface
     */
    public function createBundleItemFilter(): BundleItemFilterInterface
    {
        return new BundleItemFilter($this->getProductBundleClient());
    }

    /**
     * @return \Spryker\Glue\ProductBundleCartsRestApi\Processor\Expander\QuoteBundleItemExpanderInterface
     */
    public function createQuoteBundleItemExpander(): QuoteBundleItemExpanderInterface
    {
        return new QuoteBundleItemExpander();
    }

    /**
     * @return \Spryker\Glue\ProductBundleCartsRestApi\Dependency\Client\ProductBundleCartsRestApiToProductBundleClientInterface
     */
    public function getProductBundleClient(): ProductBundleCartsRestApiToProductBundleClientInterface
    {
        return $this->getProvidedDependency(ProductBundleCartsRestApiDependencyProvider::CLIENT_PRODUCT_BUNDLE);
    }

    /**
     * @return \Spryker\Glue\ProductBundleCartsRestApi\Dependency\RestResource\ProductBundleCartsRestApiToCartsRestApiResourceInterface
     */
    public function getCartsRestApiResource(): ProductBundleCartsRestApiToCartsRestApiResourceInterface
    {
        return $this->getProvidedDependency(ProductBundleCartsRestApiDependencyProvider::RESOURCE_CARTS_REST_API);
    }
}
