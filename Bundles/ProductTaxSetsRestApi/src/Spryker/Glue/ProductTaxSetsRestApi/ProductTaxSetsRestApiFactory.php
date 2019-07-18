<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiToTaxProductStorageClientInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiToTaxStorageClientInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\Expander\ProductTaxSetRelationshipExpander;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\Expander\ProductTaxSetRelationshipExpanderInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetResourceMapper;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetResourceMapperInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\ProductTaxSet\ProductTaxSetReader;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\ProductTaxSet\ProductTaxSetReaderInterface;

class ProductTaxSetsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductTaxSetsRestApi\Processor\ProductTaxSet\ProductTaxSetReaderInterface
     */
    public function createProductTaxSetReader(): ProductTaxSetReaderInterface
    {
        return new ProductTaxSetReader(
            $this->getTaxProductStorageClient(),
            $this->getTaxStorageClient(),
            $this->getResourceBuilder(),
            $this->createProductTaxSetResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductTaxSetsRestApi\Processor\Expander\ProductTaxSetRelationshipExpanderInterface
     */
    public function createProductTaxSetRelationshipExpander(): ProductTaxSetRelationshipExpanderInterface
    {
        return new ProductTaxSetRelationshipExpander($this->createProductTaxSetReader());
    }

    /**
     * @return \Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetResourceMapperInterface
     */
    public function createProductTaxSetResourceMapper(): ProductTaxSetResourceMapperInterface
    {
        return new ProductTaxSetResourceMapper();
    }

    /**
     * @return \Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiToTaxProductStorageClientInterface
     */
    public function getTaxProductStorageClient(): ProductTaxSetsRestApiToTaxProductStorageClientInterface
    {
        return $this->getProvidedDependency(ProductTaxSetsRestApiDependencyProvider::CLIENT_TAX_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiToTaxStorageClientInterface
     */
    public function getTaxStorageClient(): ProductTaxSetsRestApiToTaxStorageClientInterface
    {
        return $this->getProvidedDependency(ProductTaxSetsRestApiDependencyProvider::CLIENT_TAX_STORAGE);
    }
}
