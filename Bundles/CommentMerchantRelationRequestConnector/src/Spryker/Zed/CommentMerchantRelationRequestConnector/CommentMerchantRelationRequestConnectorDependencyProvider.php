<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationRequestConnector;

use Spryker\Zed\CommentMerchantRelationRequestConnector\Dependency\Facade\CommentMerchantRelationRequestConnectorToCommentFacadeBridge;
use Spryker\Zed\CommentMerchantRelationRequestConnector\Dependency\Facade\CommentMerchantRelationRequestConnectorToMerchantRelationRequestFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CommentMerchantRelationRequestConnector\CommentMerchantRelationRequestConnectorConfig getConfig()
 */
class CommentMerchantRelationRequestConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_COMMENT = 'FACADE_COMMENT';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_RELATION_REQUEST = 'FACADE_MERCHANT_RELATION_REQUEST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addCommentFacade($container);
        $container = $this->addMerchantRelationRequestFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCommentFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMMENT, function (Container $container) {
            return new CommentMerchantRelationRequestConnectorToCommentFacadeBridge(
                $container->getLocator()->comment()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationRequestFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_RELATION_REQUEST, function (Container $container) {
            return new CommentMerchantRelationRequestConnectorToMerchantRelationRequestFacadeBridge(
                $container->getLocator()->merchantRelationRequest()->facade(),
            );
        });

        return $container;
    }
}
