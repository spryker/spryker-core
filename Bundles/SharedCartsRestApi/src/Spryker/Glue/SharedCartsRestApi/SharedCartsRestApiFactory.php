<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToProductStorageClientInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\AbstractProducts\AbstractProductsReader;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReader;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface;

class SharedCartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\Mapper\AbstractProductsResourceMapperInterface
     */
    public function createAbstractProductsResourceMapper(): AbstractProductsResourceMapperInterface
    {
        return new AbstractProductsResourceMapper();
    }

    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface
     */
    public function createSharedCartReader(): SharedCartReaderInterface
    {
        return new SharedCartReader(
        );
    }

    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\Expander\ConcreteProductsRelationshipExpanderInterface
     */
    public function createConcreteProductsRelationshipExpander(): ConcreteProductsRelationshipExpanderInterface
    {
        return new ConcreteProductsRelationshipExpander($this->createConcreteProductsReader());
    }
}
