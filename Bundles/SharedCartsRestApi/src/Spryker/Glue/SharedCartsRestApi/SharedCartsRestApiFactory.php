<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi;

use Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface;
use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartsResourceMapper;
use Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartsResourceMapperInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship\SharedCartExpander;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship\SharedCartExpanderInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReader;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface;

class SharedCartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartsResourceMapperInterface
     */
    public function createSharedCartsRestMapper(): SharedCartsResourceMapperInterface
    {
        return new SharedCartsResourceMapper();
    }

    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship\SharedCartExpanderInterface
     */
    public function createSharedCartsRestExpander(): SharedCartExpanderInterface
    {
        return new SharedCartExpander(
            $this->createSharedCartReader(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface
     */
    public function createSharedCartReader(): SharedCartReaderInterface
    {
        return new SharedCartReader(
            $this->getSharedCartsRestApiClient(),
            $this->createSharedCartsRestMapper()
        );
    }

    /**
     * @return \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface
     */
    public function getSharedCartsRestApiClient(): SharedCartsRestApiClientInterface
    {
        return $this->getProvidedDependency(SharedCartsRestApiDependencyProvider::CLIENT_SHARED_CARTS_REST_API);
    }
}
