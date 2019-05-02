<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartMapper;
use Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartMapperInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship\SharedCartExpander;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship\SharedCartExpanderInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReader;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface;

/**
 * @method \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface getClient()
 */
class SharedCartsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\Mapper\SharedCartMapperInterface
     */
    public function createSharedCartMapper(): SharedCartMapperInterface
    {
        return new SharedCartMapper();
    }

    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship\SharedCartExpanderInterface
     */
    public function createSharedCartExpander(): SharedCartExpanderInterface
    {
        return new SharedCartExpander(
            $this->createSharedCartReader(),
            $this->getResourceBuilder(),
            $this->createSharedCartMapper()
        );
    }

    /**
     * @return \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface
     */
    public function createSharedCartReader(): SharedCartReaderInterface
    {
        return new SharedCartReader(
            $this->getClient()
        );
    }
}
