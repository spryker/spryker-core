<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListsRestApi;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToZedRequestClientInterface;
use Spryker\Client\ShoppingListsRestApi\Zed\ShoppingListsRestApiStub;
use Spryker\Client\ShoppingListsRestApi\Zed\ShoppingListsRestApiStubInterface;

class ShoppingListsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ShoppingListsRestApi\Zed\ShoppingListsRestApiStubInterface
     */
    public function createShoppingListsRestApiStub(): ShoppingListsRestApiStubInterface
    {
        return new ShoppingListsRestApiStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToZedRequestClientInterface
     */
    public function getZedRequestClient(): ShoppingListsRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(ShoppingListsRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
