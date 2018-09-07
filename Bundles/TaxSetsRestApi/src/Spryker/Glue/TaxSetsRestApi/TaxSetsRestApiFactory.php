<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxSetsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\TaxSetsRestApi\Dependency\Client\TaxSetsRestApiToTaxProductConnectorClientInterface;
use Spryker\Glue\TaxSetsRestApi\Processor\Mapper\TaxSetsResourceMapper;
use Spryker\Glue\TaxSetsRestApi\Processor\Mapper\TaxSetsResourceMapperInterface;
use Spryker\Glue\TaxSetsRestApi\Processor\TaxSets\TaxSetsReader;
use Spryker\Glue\TaxSetsRestApi\Processor\TaxSets\TaxSetsReaderInterface;

class TaxSetsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\TaxSetsRestApi\Dependency\Client\TaxSetsRestApiToTaxProductConnectorClientInterface
     */
    public function getTaxProductConnectorClient(): TaxSetsRestApiToTaxProductConnectorClientInterface
    {
        return $this->getProvidedDependency(TaxSetsRestApiDependencyProvider::CLIENT_TAX_PRODUCT_CONNECTOR);
    }

    /**
     * @return \Spryker\Glue\TaxSetsRestApi\Processor\Mapper\TaxSetsResourceMapperInterface
     */
    public function createTaxSetsResourceMapper(): TaxSetsResourceMapperInterface
    {
        return new TaxSetsResourceMapper();
    }

    /**
     * @return \Spryker\Glue\TaxSetsRestApi\Processor\TaxSets\TaxSetsReaderInterface
     */
    public function createTaxSetsReader(): TaxSetsReaderInterface
    {
        return new TaxSetsReader(
            $this->getTaxProductConnectorClient(),
            $this->getResourceBuilder(),
            $this->createTaxSetsResourceMapper()
        );
    }
}
