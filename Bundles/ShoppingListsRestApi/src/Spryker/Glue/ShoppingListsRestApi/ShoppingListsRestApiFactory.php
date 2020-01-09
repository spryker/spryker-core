<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ShoppingListsRestApi\Processor\Customer\Reader\CustomerRestRequestReader;
use Spryker\Glue\ShoppingListsRestApi\Processor\Customer\Reader\CustomerRestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Builder\ShoppingListRestResponseBuilder;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Builder\ShoppingListRestResponseBuilderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Mapper\ShoppingListMapper;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Mapper\ShoppingListMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader\ShoppingListRestRequestReader;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader\ShoppingListRestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListCreator;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListCreatorInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListDeleter;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListDeleterInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListReader;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListUpdater;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListUpdaterInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Builder\ShoppingListItemRestResponseBuilder;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Builder\ShoppingListItemRestResponseBuilderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Mapper\ShoppingListItemMapper;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Mapper\ShoppingListItemMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Reader\ShoppingListItemRestRequestReader;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Reader\ShoppingListItemRestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemAdder;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemAdderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemDeleter;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemUpdater;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemUpdaterInterface;

/**
 * @method \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface getClient()
 */
class ShoppingListsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListReaderInterface
     */
    public function createShoppingListReader(): ShoppingListReaderInterface
    {
        return new ShoppingListReader(
            $this->getClient(),
            $this->createShoppingListRestRequestReader(),
            $this->createCustomerRestRequestReader(),
            $this->createShoppingListRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListCreatorInterface
     */
    public function createShoppingListCreator(): ShoppingListCreatorInterface
    {
        return new ShoppingListCreator(
            $this->getClient(),
            $this->createShoppingListMapper(),
            $this->createShoppingListRestRequestReader(),
            $this->createShoppingListRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListUpdaterInterface
     */
    public function createShoppingListUpdater(): ShoppingListUpdaterInterface
    {
        return new ShoppingListUpdater(
            $this->getClient(),
            $this->createShoppingListMapper(),
            $this->createShoppingListRestRequestReader(),
            $this->createShoppingListRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListDeleterInterface
     */
    public function createShoppingListDeleter(): ShoppingListDeleterInterface
    {
        return new ShoppingListDeleter(
            $this->getClient(),
            $this->createShoppingListRestRequestReader(),
            $this->createShoppingListRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemAdderInterface
     */
    public function createShoppingListItemAdder(): ShoppingListItemAdderInterface
    {
        return new ShoppingListItemAdder(
            $this->getClient(),
            $this->createShoppingListItemMapper(),
            $this->createShoppingListItemRestRequestReader(),
            $this->createShoppingListItemRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemUpdaterInterface
     */
    public function createShoppingListItemUpdater(): ShoppingListItemUpdaterInterface
    {
        return new ShoppingListItemUpdater(
            $this->getClient(),
            $this->createShoppingListItemMapper(),
            $this->createShoppingListItemRestRequestReader(),
            $this->createShoppingListItemRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemDeleter
     */
    public function createShoppingListItemDeleter(): ShoppingListItemDeleter
    {
        return new ShoppingListItemDeleter(
            $this->getClient(),
            $this->createShoppingListItemRestRequestReader(),
            $this->createShoppingListItemRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Mapper\ShoppingListMapperInterface
     */
    public function createShoppingListMapper(): ShoppingListMapperInterface
    {
        return new ShoppingListMapper();
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Mapper\ShoppingListItemMapperInterface
     */
    public function createShoppingListItemMapper(): ShoppingListItemMapperInterface
    {
        return new ShoppingListItemMapper();
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\Customer\Reader\CustomerRestRequestReaderInterface
     */
    public function createCustomerRestRequestReader(): CustomerRestRequestReaderInterface
    {
        return new CustomerRestRequestReader();
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader\ShoppingListRestRequestReaderInterface
     */
    public function createShoppingListRestRequestReader(): ShoppingListRestRequestReaderInterface
    {
        return new ShoppingListRestRequestReader(
            $this->createCustomerRestRequestReader()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Reader\ShoppingListItemRestRequestReaderInterface
     */
    public function createShoppingListItemRestRequestReader(): ShoppingListItemRestRequestReaderInterface
    {
        return new ShoppingListItemRestRequestReader(
            $this->createShoppingListRestRequestReader()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Builder\ShoppingListRestResponseBuilderInterface
     */
    public function createShoppingListRestResponseBuilder(): ShoppingListRestResponseBuilderInterface
    {
        return new ShoppingListRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createShoppingListMapper(),
            $this->createShoppingListItemRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Builder\ShoppingListItemRestResponseBuilderInterface
     */
    public function createShoppingListItemRestResponseBuilder(): ShoppingListItemRestResponseBuilderInterface
    {
        return new ShoppingListItemRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createShoppingListItemMapper()
        );
    }
}
