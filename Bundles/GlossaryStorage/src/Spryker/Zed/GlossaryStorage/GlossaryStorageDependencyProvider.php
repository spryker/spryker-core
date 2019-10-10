<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage;

use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Spryker\Zed\GlossaryStorage\Dependency\Facade\GlossaryStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\GlossaryStorage\Dependency\QueryContainer\GlossaryStorageToGlossaryQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 */
class GlossaryStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const QUERY_CONTAINER_GLOSSARY = 'QUERY_CONTAINER_GLOSSARY';
    public const PROPEL_QUERY_GLOSSARY_TRANSLATION = 'PROPEL_QUERY_GLOSSARY_TRANSLATE';
    public const PROPEL_QUERY_GLOSSARY_KEY = 'PROPEL_QUERY_GLOSSARY_KEY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addEventBehaviorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->addEventBehaviorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_GLOSSARY, function (Container $container) {
            return new GlossaryStorageToGlossaryQueryContainerBridge($container->getLocator()->glossary()->queryContainer());
        });

        $this->addGlossaryTranslateQuery($container);
        $this->addGlossaryKeyQuery($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addEventBehaviorFacade(Container $container): void
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new GlossaryStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addGlossaryTranslateQuery(Container $container): void
    {
        $container->set(static::PROPEL_QUERY_GLOSSARY_TRANSLATION, function (): SpyGlossaryTranslationQuery {
            return SpyGlossaryTranslationQuery::create();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addGlossaryKeyQuery(Container $container): void
    {
        $container->set(static::PROPEL_QUERY_GLOSSARY_KEY, function (): SpyGlossaryKeyQuery {
            return SpyGlossaryKeyQuery::create();
        });
    }
}
