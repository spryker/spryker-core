<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment;

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
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCommentValidatorPlugins($container);

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
     * @return array<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentValidatorPluginInterface>
     */
    protected function getCommentValidatorPlugins(): array
    {
        return [];
    }
}
