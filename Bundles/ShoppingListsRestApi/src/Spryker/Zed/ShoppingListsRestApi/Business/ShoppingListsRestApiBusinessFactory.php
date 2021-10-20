<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapper;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapperInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListCreator;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListCreatorInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListDeleter;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListDeleterInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListReader;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListReaderInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListUpdater;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListUpdaterInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\Mapper\ShoppingListItemMapper;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\Mapper\ShoppingListItemMapperInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemAdder;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemAdderInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemDeleter;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemDeleterInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemReader;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemReaderInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemUpdater;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemUpdaterInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;
use Spryker\Zed\ShoppingListsRestApi\ShoppingListsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\ShoppingListsRestApi\ShoppingListsRestApiConfig getConfig()
 */
class ShoppingListsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListReaderInterface
     */
    public function createShoppingListReader(): ShoppingListReaderInterface
    {
        return new ShoppingListReader(
            $this->getShoppingListFacade(),
            $this->createShoppingListMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListCreatorInterface
     */
    public function createShoppingListCreator(): ShoppingListCreatorInterface
    {
        return new ShoppingListCreator(
            $this->getShoppingListFacade(),
            $this->createShoppingListMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListUpdaterInterface
     */
    public function createShoppingListUpdater(): ShoppingListUpdaterInterface
    {
        return new ShoppingListUpdater(
            $this->getShoppingListFacade(),
            $this->createShoppingListMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListDeleterInterface
     */
    public function createShoppingListDeleter(): ShoppingListDeleterInterface
    {
        return new ShoppingListDeleter(
            $this->getShoppingListFacade(),
            $this->createShoppingListMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemAdderInterface
     */
    public function createShoppingListItemAdder(): ShoppingListItemAdderInterface
    {
        return new ShoppingListItemAdder(
            $this->getShoppingListFacade(),
            $this->createShoppingListItemMapper(),
            $this->createShoppingListReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemUpdaterInterface
     */
    public function createShoppingListItemUpdater(): ShoppingListItemUpdaterInterface
    {
        return new ShoppingListItemUpdater(
            $this->getShoppingListFacade(),
            $this->createShoppingListItemMapper(),
            $this->createShoppingListItemReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemDeleterInterface
     */
    public function createShoppingListItemDeleter(): ShoppingListItemDeleterInterface
    {
        return new ShoppingListItemDeleter(
            $this->getShoppingListFacade(),
            $this->createShoppingListItemMapper(),
            $this->createShoppingListItemReader(),
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\ShoppingListItemReaderInterface
     */
    public function createShoppingListItemReader(): ShoppingListItemReaderInterface
    {
        return new ShoppingListItemReader(
            $this->getShoppingListFacade(),
            $this->createShoppingListItemMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingListItem\Mapper\ShoppingListItemMapperInterface
     */
    public function createShoppingListItemMapper(): ShoppingListItemMapperInterface
    {
        return new ShoppingListItemMapper();
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapperInterface
     */
    public function createShoppingListMapper(): ShoppingListMapperInterface
    {
        return new ShoppingListMapper();
    }

    /**
     * @return \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface
     */
    public function getShoppingListFacade(): ShoppingListsRestApiToShoppingListFacadeInterface
    {
        return $this->getProvidedDependency(ShoppingListsRestApiDependencyProvider::FACADE_SHOPPING_LIST);
    }
}
