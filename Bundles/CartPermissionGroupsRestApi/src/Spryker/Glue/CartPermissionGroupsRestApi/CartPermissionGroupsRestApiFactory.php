<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi;

use Spryker\Glue\CartPermissionGroupsRestApi\Dependency\Client\CartPermissionGroupsRestApiToSharedCartClientInterface;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\CartPermissionGroupReader;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\CartPermissionGroupReaderInterface;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper\CartPermissionGroupMapper;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper\CartPermissionGroupMapperInterface;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupsResponseBuilder;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupsResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CartPermissionGroupsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper\CartPermissionGroupMapperInterface
     */
    public function createCartPermissionGroupMapper(): CartPermissionGroupMapperInterface
    {
        return new CartPermissionGroupMapper();
    }

    /**
     * @return \Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupsResponseBuilderInterface
     */
    public function createCartPermissionGroupsResponseBuilder(): CartPermissionGroupsResponseBuilderInterface
    {
        return new CartPermissionGroupsResponseBuilder(
            $this->createCartPermissionGroupMapper(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\CartPermissionGroupReaderInterface
     */
    public function createCartPermissionGroupReader(): CartPermissionGroupReaderInterface
    {
        return new CartPermissionGroupReader(
            $this->getSharedCartClient(),
            $this->createCartPermissionGroupsResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CartPermissionGroupsRestApi\Dependency\Client\CartPermissionGroupsRestApiToSharedCartClientInterface
     */
    public function getSharedCartClient(): CartPermissionGroupsRestApiToSharedCartClientInterface
    {
        return $this->getProvidedDependency(CartPermissionGroupsRestApiDependencyProvider::CLIENT_SHARED_CART);
    }
}
