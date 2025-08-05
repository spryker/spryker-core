<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Resolver;

use Generated\Shared\Transfer\ShopContextTransfer;
use Spryker\Shared\Kernel\ContainerInterface;

class ShopContextResolver implements ShopContextResolverInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SHOP_CONTEXT = 'SERVICE_SHOP_CONTEXT';

    /**
     * @var \Spryker\Shared\Kernel\ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(): ShopContextTransfer
    {
        return $this->container->hasApplicationService(static::SERVICE_SHOP_CONTEXT)
            ? $this->container->getApplicationService(static::SERVICE_SHOP_CONTEXT)
            : new ShopContextTransfer();
    }
}
