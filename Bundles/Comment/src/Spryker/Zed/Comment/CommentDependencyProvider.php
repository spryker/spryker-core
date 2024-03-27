<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment;

use Spryker\Zed\Comment\Dependency\Facade\CommentToCustomerFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Comment\CommentConfig getConfig()
 */
class CommentDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_COMMENT_VALIDATOR = 'PLUGINS_COMMENT_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY = 'PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY';

    /**
     * @var string
     */
    public const PLUGINS_COMMENT_EXPANDER = 'PLUGINS_COMMENT_EXPANDER';

    /**
     * @var string
     */
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addCommentValidatorPlugins($container);
        $container = $this->addCommentAuthorValidatorStrategyPlugins($container);
        $container = $this->addCommentExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCommentValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMMENT_VALIDATOR, function () {
            return $this->getCommentValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCommentAuthorValidatorStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMMENT_AUTHOR_VALIDATOR_STRATEGY, function () {
            return $this->getCommentAuthorValidatorStrategyPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCommentExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_COMMENT_EXPANDER, function () {
            return $this->getCommentExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container->set(static::FACADE_CUSTOMER, function (Container $container) {
            return new CommentToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentValidatorPluginInterface>
     */
    protected function getCommentValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentAuthorValidatorStrategyPluginInterface>
     */
    protected function getCommentAuthorValidatorStrategyPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentExpanderPluginInterface>
     */
    protected function getCommentExpanderPlugins(): array
    {
        return [];
    }
}
