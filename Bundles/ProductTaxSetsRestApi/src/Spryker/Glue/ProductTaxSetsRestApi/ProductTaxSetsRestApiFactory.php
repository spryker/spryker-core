<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiApiToTaxProductConnectorClientInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetsResourceMapper;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetsResourceMapperInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\TaxSets\ProductTaxSetsReader;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\TaxSets\ProductTaxSetsReaderInterface;

class ProductTaxSetsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiApiToTaxProductConnectorClientInterface
     */
    public function getTaxProductConnectorClient(): ProductTaxSetsRestApiApiToTaxProductConnectorClientInterface
    {
        return $this->getProvidedDependency(ProductTaxSetsRestApiDependencyProvider::CLIENT_TAX_PRODUCT_CONNECTOR);
    }

    /**
     * @return \Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetsResourceMapperInterface
     */
    public function createTaxSetsResourceMapper(): ProductTaxSetsResourceMapperInterface
    {
        return new ProductTaxSetsResourceMapper();
    }

    /**
     * @return \Spryker\Glue\ProductTaxSetsRestApi\Processor\TaxSets\ProductTaxSetsReaderInterface
     */
    public function createTaxSetsReader(): ProductTaxSetsReaderInterface
    {
        return new ProductTaxSetsReader(
            $this->getTaxProductConnectorClient(),
            $this->getResourceBuilder(),
            $this->createTaxSetsResourceMapper()
        );
    }
}
