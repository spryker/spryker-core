<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui;

use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Category\Persistence\SpyCategoryTemplateQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeBridge;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToLocaleFacadeBridge;
use Spryker\Zed\CategoryGui\Dependency\Service\CategoryGuiToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CategoryGui\CategoryGuiConfig getConfig()
 */
class CategoryGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_CATEGORY = 'FACADE_CATEGORY';

    public const PLUGINS_CATEGORY_FORM = 'PLUGINS_CATEGORY_FORM';
    public const PLUGINS_CATEGORY_FORM_TAB_EXPANDER = 'PLUGINS_CATEGORY_FORM_TAB_EXPANDER';
    public const PLUGINS_CATEGORY_RELATION_READ = 'PLUGINS_CATEGORY_RELATION_READ';

    public const PROPEL_QUERY_CATEGORY = 'PROPEL_QUERY_CATEGORY';
    public const PROPEL_QUERY_CATEGORY_TEMPLATE = 'PROPEL_QUERY_CATEGORY_TEMPLATE';
    public const PROPEL_QUERY_CATEGORY_NODE = 'PROPEL_QUERY_CATEGORY_NODE';
    public const PROPEL_QUERY_URL = 'PROPEL_QUERY_URL';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @uses \Spryker\Zed\Form\Communication\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_CSRF_PROVIDER
     */
    public const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addLocaleFacade($container);
        $container = $this->addCategoryFacade($container);
        $container = $this->addCategoryFormPlugins($container);
        $container = $this->addCategoryFormTabExpanderPlugins($container);
        $container = $this->addCategoryRelationReadPlugins($container);
        $container = $this->addCsrfProviderService($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = $this->addCategoryPropelQuery($container);
        $container = $this->addCategoryTemplatePropelQuery($container);
        $container = $this->addCategoryNodePropelQuery($container);
        $container = $this->addUrlPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new CategoryGuiToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_CATEGORY, function (Container $container) {
            return new CategoryGuiToCategoryFacadeBridge(
                $container->getLocator()->category()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new CategoryGuiToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @module Category
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CATEGORY, $container->factory(function () {
            return SpyCategoryQuery::create();
        }));

        return $container;
    }

    /**
     * @module Category
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryTemplatePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CATEGORY_TEMPLATE, $container->factory(function () {
            return SpyCategoryTemplateQuery::create();
        }));

        return $container;
    }

    /**
     * @module Category
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryNodePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CATEGORY_NODE, $container->factory(function () {
            return SpyCategoryNodeQuery::create();
        }));

        return $container;
    }

    /**
     * @module Url
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUrlPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_URL, $container->factory(function () {
            return SpyUrlQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryFormPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CATEGORY_FORM, function () {
            return $this->getCategoryFormPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryFormPluginInterface[]
     */
    protected function getCategoryFormPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryFormTabExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CATEGORY_FORM_TAB_EXPANDER, function () {
            return $this->getCategoryFormTabExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryFormTabExpanderPluginInterface[]
     */
    protected function getCategoryFormTabExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryRelationReadPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CATEGORY_RELATION_READ, function () {
            return $this->getCategoryRelationReadPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryRelationReadPluginInterface[]
     */
    protected function getCategoryRelationReadPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCsrfProviderService(Container $container): Container
    {
        $container->set(static::SERVICE_FORM_CSRF_PROVIDER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_FORM_CSRF_PROVIDER);
        });

        return $container;
    }
}
