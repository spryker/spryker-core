<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOption\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOption\ShoppingListProductOptionReader;
use Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOption\ShoppingListProductOptionReaderInterface;
use Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOption\ShoppingListProductOptionWriter;
use Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOption\ShoppingListProductOptionWriterInterface;
use Spryker\Zed\ShoppingListProductOption\Dependency\Facade\ShoppingListProductOptionToProductOptionFacadeInterface;
use Spryker\Zed\ShoppingListProductOption\ShoppingListProductOptionDependencyProvider;

/**
 * @method \Spryker\Zed\ShoppingListProductOption\ShoppingListProductOptionConfig getConfig()
 * @method \Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOptionBusinessFactory getFactory()
 * @method \Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ShoppingListProductOption\Persistence\ShoppingListProductOptionRepositoryInterface getRepository()
 */
class ShoppingListProductOptionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOption\ShoppingListProductOptionReaderInterface
     */
    public function createShoppingListItemProductOptionReader(): ShoppingListProductOptionReaderInterface
    {
        return new ShoppingListProductOptionReader(
            $this->getProductOptionFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListProductOption\Business\ShoppingListProductOption\ShoppingListProductOptionWriterInterface
     */
    public function createShoppingListProductOptionWriter(): ShoppingListProductOptionWriterInterface
    {
        return new ShoppingListProductOptionWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListProductOption\Dependency\Facade\ShoppingListProductOptionToProductOptionFacadeInterface
     */
    public function getProductOptionFacade(): ShoppingListProductOptionToProductOptionFacadeInterface
    {
        return $this->getProvidedDependency(ShoppingListProductOptionDependencyProvider::FACADE_PRODUCT_OPTION);
    }
}
