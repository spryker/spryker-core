<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListProductOptionConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\Mapper\CartItemToShoppingListItemMapper;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\Mapper\CartItemToShoppingListItemMapperInterface;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\ProductOption\ProductOptionValuesRemover;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\ProductOption\ProductOptionValuesRemoverInterface;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListItem\ShoppingListItemExpander;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListItem\ShoppingListItemExpanderInterface;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionReader;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionReaderInterface;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionWriter;
use Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionWriterInterface;
use Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface;
use Spryker\Zed\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\ShoppingListProductOptionConnectorConfig getConfig()
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOptionConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ShoppingListProductOptionConnector\Persistence\ShoppingListProductOptionConnectorRepositoryInterface getRepository()
 */
class ShoppingListProductOptionConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionReaderInterface
     */
    public function createShoppingListItemProductOptionReader(): ShoppingListProductOptionReaderInterface
    {
        return new ShoppingListProductOptionReader(
            $this->getProductOptionFacade(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListProductOption\ShoppingListProductOptionWriterInterface
     */
    public function createShoppingListProductOptionWriter(): ShoppingListProductOptionWriterInterface
    {
        return new ShoppingListProductOptionWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListProductOptionConnector\Business\ShoppingListItem\ShoppingListItemExpanderInterface
     */
    public function createShoppingListItemExpander(): ShoppingListItemExpanderInterface
    {
        return new ShoppingListItemExpander(
            $this->createShoppingListItemProductOptionReader()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListProductOptionConnector\Business\Mapper\CartItemToShoppingListItemMapperInterface
     */
    public function createCartItemToShoppingListItemMapper(): CartItemToShoppingListItemMapperInterface
    {
        return new CartItemToShoppingListItemMapper();
    }

    /**
     * @return \Spryker\Zed\ShoppingListProductOptionConnector\Dependency\Facade\ShoppingListProductOptionConnectorToProductOptionFacadeInterface
     */
    public function getProductOptionFacade(): ShoppingListProductOptionConnectorToProductOptionFacadeInterface
    {
        return $this->getProvidedDependency(ShoppingListProductOptionConnectorDependencyProvider::FACADE_PRODUCT_OPTION);
    }

    /**
     * @return \Spryker\Zed\ShoppingListProductOptionConnector\Business\ProductOption\ProductOptionValuesRemoverInterface
     */
    public function createProductOptionValuesRemover(): ProductOptionValuesRemoverInterface
    {
        return new ProductOptionValuesRemover(
            $this->getEntityManager()
        );
    }
}
