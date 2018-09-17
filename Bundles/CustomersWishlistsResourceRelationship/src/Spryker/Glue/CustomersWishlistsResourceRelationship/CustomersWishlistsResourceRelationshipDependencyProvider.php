<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersWishlistsResourceRelationship;

use Spryker\Glue\CustomersWishlistsResourceRelationship\Dependency\RestResource\CustomersToWishlistsRestApiBridge;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;

class CustomersWishlistsResourceRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    public const RESOURCE_WISHLISTS = 'RESOURCE_WISHLISTS';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addWishlistsResource($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addWishlistsResource(Container $container): Container
    {
        $container[static::RESOURCE_WISHLISTS] = function (Container $container) {
            return new CustomersToWishlistsRestApiBridge(
                $container->getLocator()->wishlistsRestApi()->resource()
            );
        };

        return $container;
    }
}
