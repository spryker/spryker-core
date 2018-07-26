<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingList\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShoppingList\Business\Installer\ShoppingListPermissionInstaller;
use Spryker\Zed\ShoppingList\Business\Installer\ShoppingListPermissionInstallerInterface;
use Spryker\Zed\ShoppingList\Business\Model\QuoteToShoppingListConverter;
use Spryker\Zed\ShoppingList\Business\Model\QuoteToShoppingListConverterInterface;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListItemOperation;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListItemOperationInterface;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListReader;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListReaderInterface;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolver;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListSharer;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListSharerInterface;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListWriter;
use Spryker\Zed\ShoppingList\Business\Model\ShoppingListWriterInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPermissionFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPersistentCartFacadeInterface;
use Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface;
use Spryker\Zed\ShoppingList\ShoppingListDependencyProvider;

/**
 * @method \Spryker\Zed\ShoppingList\Persistence\ShoppingListEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ShoppingList\Persistence\ShoppingListRepositoryInterface getRepository()
 * @method \Spryker\Zed\ShoppingList\ShoppingListConfig getConfig()
 */
class ShoppingListBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShoppingList\Business\Model\ShoppingListReaderInterface
     */
    public function createShoppingListReader(): ShoppingListReaderInterface
    {
        return new ShoppingListReader(
            $this->getRepository(),
            $this->getProductFacade(),
            $this->getCompanyUserFacade(),
            $this->getItemExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Business\Model\ShoppingListWriterInterface
     */
    public function createShoppingListWriter(): ShoppingListWriterInterface
    {
        return new ShoppingListWriter(
            $this->getEntityManager(),
            $this->getProductFacade(),
            $this->getRepository(),
            $this->getConfig(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Business\Model\ShoppingListResolverInterface
     */
    public function createShoppingListResolver(): ShoppingListResolverInterface
    {
        return new ShoppingListResolver(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getMessengerFacade(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Business\Model\ShoppingListItemOperationInterface
     */
    public function createShoppingListItemOperation(): ShoppingListItemOperationInterface
    {
        return new ShoppingListItemOperation(
            $this->getEntityManager(),
            $this->getProductFacade(),
            $this->getRepository(),
            $this->createShoppingListResolver(),
            $this->getMessengerFacade(),
            $this->getAddItemPreCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Business\Model\QuoteToShoppingListConverterInterface
     */
    public function createQuoteToShoppingListConverter(): QuoteToShoppingListConverterInterface
    {
        return new QuoteToShoppingListConverter(
            $this->createShoppingListResolver(),
            $this->getEntityManager(),
            $this->getPersistentCartFacade(),
            $this->getQuoteItemExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Business\Model\ShoppingListSharerInterface
     */
    public function createShoppingListSharer(): ShoppingListSharerInterface
    {
        return new ShoppingListSharer(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\ItemExpanderPluginInterface[]
     */
    public function getItemExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::PLUGINS_ITEM_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToProductFacadeInterface
     */
    public function getProductFacade(): ShoppingListToProductFacadeInterface
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Business\Installer\ShoppingListPermissionInstallerInterface
     */
    public function createShoppingListPermissionInstaller(): ShoppingListPermissionInstallerInterface
    {
        return new ShoppingListPermissionInstaller($this->getConfig(), $this->getEntityManager(), $this->getPermissionFacade());
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPermissionFacadeInterface
     */
    public function getPermissionFacade(): ShoppingListToPermissionFacadeInterface
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::FACADE_PERMISSION);
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): ShoppingListToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToPersistentCartFacadeInterface
     */
    public function getPersistentCartFacade(): ShoppingListToPersistentCartFacadeInterface
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::FACADE_PERSISTENT_CART);
    }

    /**
     * @return \Spryker\Zed\ShoppingList\Dependency\Facade\ShoppingListToMessengerFacadeInterface
     */
    public function getMessengerFacade(): ShoppingListToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\QuoteItemsPreConvertPluginInterface[]
     */
    public function getQuoteItemExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::PLUGINS_QUOTE_ITEM_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ShoppingListExtension\Dependency\Plugin\AddItemPreCheckPluginInterface[]
     */
    public function getAddItemPreCheckPlugins(): array
    {
        return $this->getProvidedDependency(ShoppingListDependencyProvider::PLUGINS_ADD_ITEM_PRE_CHECK);
    }
}
