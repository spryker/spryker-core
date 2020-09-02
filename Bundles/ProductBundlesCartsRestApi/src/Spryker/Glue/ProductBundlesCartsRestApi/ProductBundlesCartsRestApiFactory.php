<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesCartsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductBundlesCartsRestApi\Dependency\Client\ProductBundlesCartsRestApiToProductBundleClientInterface;
use Spryker\Glue\ProductBundlesCartsRestApi\Dependency\RestResource\ProductBundlesCartsRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\ProductBundlesCartsRestApi\Processor\Expander\BundleItemExpander;
use Spryker\Glue\ProductBundlesCartsRestApi\Processor\Expander\BundleItemExpanderInterface;
use Spryker\Glue\ProductBundlesCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilder;
use Spryker\Glue\ProductBundlesCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilderInterface;

class ProductBundlesCartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductBundlesCartsRestApi\Processor\Expander\BundleItemExpanderInterface
     */
    public function createBundleItemExpander(): BundleItemExpanderInterface
    {
        return new BundleItemExpander(
            $this->getProductBundleClient(),
            $this->getCartsRestApiResource(),
            $this->createBundleItemRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductBundlesCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilderInterface
     */
    public function createBundleItemRestResponseBuilder(): BundleItemRestResponseBuilderInterface
    {
        return new BundleItemRestResponseBuilder(
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductBundlesCartsRestApi\Dependency\Client\ProductBundlesCartsRestApiToProductBundleClientInterface
     */
    public function getProductBundleClient(): ProductBundlesCartsRestApiToProductBundleClientInterface
    {
        return $this->getProvidedDependency(ProductBundlesCartsRestApiDependencyProvider::CLIENT_PRODUCT_BUNDLE);
    }

    /**
     * @return \Spryker\Glue\ProductBundlesCartsRestApi\Dependency\RestResource\ProductBundlesCartsRestApiToCartsRestApiResourceInterface
     */
    public function getCartsRestApiResource(): ProductBundlesCartsRestApiToCartsRestApiResourceInterface
    {
        return $this->getProvidedDependency(ProductBundlesCartsRestApiDependencyProvider::RESOURCE_CARTS_REST_API);
    }
}
