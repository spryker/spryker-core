<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemsResourceMapper;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemsResourceMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListsResourceMapper;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListsResourceMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Request\RestRequestReader;
use Spryker\Glue\ShoppingListsRestApi\Processor\Request\RestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Response\RestResponseWriter;
use Spryker\Glue\ShoppingListsRestApi\Processor\Response\RestResponseWriterInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListCreator;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListCreatorInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListDeleter;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListDeleterInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListReader;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListUpdater;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListUpdaterInterface;
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
    public function createShoppingListsReader(): ShoppingListReaderInterface
    {
        return new ShoppingListReader(
            $this->getClient(),
            $this->createShoppingListsResourceMapper(),
            $this->createRestRequestReader(),
            $this->createRestRequestWriter()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListCreatorInterface
     */
    public function createShoppingListsCreator(): ShoppingListCreatorInterface
    {
        return new ShoppingListCreator(
            $this->getClient(),
            $this->createShoppingListsResourceMapper(),
            $this->createRestRequestReader(),
            $this->createRestRequestWriter()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListUpdaterInterface
     */
    public function createShoppingListsUpdater(): ShoppingListUpdaterInterface
    {
        return new ShoppingListUpdater(
            $this->getClient(),
            $this->createShoppingListsResourceMapper(),
            $this->createRestRequestReader(),
            $this->createRestRequestWriter()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\ShoppingListDeleterInterface
     */
    public function createShoppingListsDeleter(): ShoppingListDeleterInterface
    {
        return new ShoppingListDeleter(
            $this->getClient(),
            $this->createShoppingListsResourceMapper(),
            $this->createRestRequestReader(),
            $this->createRestRequestWriter()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemAdderInterface
     */
    public function createShoppingListItemAdder(): ShoppingListItemAdderInterface
    {
        return new ShoppingListItemAdder(
            $this->getClient(),
            $this->createShoppingListItemsResourceMapper(),
            $this->createRestRequestReader(),
            $this->createRestRequestWriter()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemUpdaterInterface
     */
    public function createShoppingListItemUpdater(): ShoppingListItemUpdaterInterface
    {
        return new ShoppingListItemUpdater(
            $this->getClient(),
            $this->createShoppingListItemsResourceMapper(),
            $this->createRestRequestReader(),
            $this->createRestRequestWriter()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\ShoppingListItemDeleter
     */
    public function createShoppingListItemDeleter(): ShoppingListItemDeleter
    {
        return new ShoppingListItemDeleter(
            $this->getClient(),
            $this->createShoppingListItemsResourceMapper(),
            $this->createRestRequestReader(),
            $this->createRestRequestWriter()
        );
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListsResourceMapperInterface
     */
    public function createShoppingListsResourceMapper(): ShoppingListsResourceMapperInterface
    {
        return new ShoppingListsResourceMapper();
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListItemsResourceMapperInterface
     */
    public function createShoppingListItemsResourceMapper(): ShoppingListItemsResourceMapperInterface
    {
        return new ShoppingListItemsResourceMapper();
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\Request\RestRequestReaderInterface
     */
    public function createRestRequestReader(): RestRequestReaderInterface
    {
        return new RestRequestReader();
    }

    /**
     * @return \Spryker\Glue\ShoppingListsRestApi\Processor\Response\RestResponseWriterInterface
     */
    public function createRestRequestWriter(): RestResponseWriterInterface
    {
        return new RestResponseWriter(
            $this->getResourceBuilder(),
            $this->createShoppingListsResourceMapper(),
            $this->createShoppingListItemsResourceMapper()
        );
    }
}
