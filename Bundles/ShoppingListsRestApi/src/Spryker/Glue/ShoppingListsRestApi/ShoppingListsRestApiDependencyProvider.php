<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToGlossaryStorageClientBridge;
use Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToShoppingListClientBridge;

/**
 * @method \Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig getConfig()
 */
class ShoppingListsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SHOPPING_LIST = 'CLIENT_SHOPPING_LIST';

    /**
     * @var string
     */
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @var string
     */
    public const PLUGINS_REST_SHOPPING_LIST_ITEMS_ATTRIBUTES_MAPPER = 'PLUGINS_REST_SHOPPING_LIST_ITEMS_ATTRIBUTES_MAPPER';

    /**
     * @var string
     */
    public const PLUGINS_SHOPPING_LIST_ITEM_REQUEST_MAPPER = 'PLUGINS_SHOPPING_LIST_ITEM_REQUEST_MAPPER';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addShoppingListClient($container);
        $container = $this->addGlossaryStorageClient($container);
        $container = $this->addRestShoppingListItemsAttributesMapperPlugins($container);
        $container = $this->addShoppingListItemRequestMapperPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addShoppingListClient(Container $container): Container
    {
        $container->set(static::CLIENT_SHOPPING_LIST, function (Container $container) {
            return new ShoppingListsRestApiToShoppingListClientBridge(
                $container->getLocator()->shoppingList()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return new ShoppingListsRestApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addRestShoppingListItemsAttributesMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_REST_SHOPPING_LIST_ITEMS_ATTRIBUTES_MAPPER, function () {
            return $this->getRestShoppingListItemsAttributesMapperPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addShoppingListItemRequestMapperPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SHOPPING_LIST_ITEM_REQUEST_MAPPER, function () {
            return $this->getShoppingListItemRequestMapperPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Glue\ShoppingListsRestApiExtension\Dependency\Plugin\RestShoppingListItemsAttributesMapperPluginInterface>
     */
    protected function getRestShoppingListItemsAttributesMapperPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Glue\ShoppingListsRestApiExtension\Dependency\Plugin\ShoppingListItemRequestMapperPluginInterface>
     */
    protected function getShoppingListItemRequestMapperPlugins(): array
    {
        return [];
    }
}
