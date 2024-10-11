<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business;

use Generated\Shared\Transfer\RouterActionCollectionTransfer;
use Generated\Shared\Transfer\RouterBundleCollectionTransfer;
use Generated\Shared\Transfer\RouterControllerCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Router\Business\Router\ChainRouter;
use Spryker\Zed\Router\Business\Router\RouterInterface;

/**
 * @method \Spryker\Zed\Router\Business\RouterBusinessFactory getFactory()
 */
class RouterFacade extends AbstractFacade implements RouterFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getRouter(): ChainRouter
    {
        return $this->getFactory()->createRouter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getZedRouter(): RouterInterface
    {
        return $this->getFactory()->createZedRouter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getBackofficeChainRouter(): ChainRouter
    {
        return $this->getFactory()->createBackofficeChainRouter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getMerchantPortalChainRouter(): ChainRouter
    {
        return $this->getFactory()->createMerchantPortalChainRouter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getBackofficeRouter(): RouterInterface
    {
        return $this->getFactory()->createBackofficeRouter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getMerchantPortalRouter(): RouterInterface
    {
        return $this->getFactory()->createMerchantPortalRouter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getBackendGatewayChainRouter(): ChainRouter
    {
        return $this->getFactory()->createBackendGatewayChainRouter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getBackendGatewayRouter(): RouterInterface
    {
        return $this->getFactory()->createBackendGatewayRouter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getBackendApiChainRouter(): ChainRouter
    {
        return $this->getFactory()->createBackendApiChainRouter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getZedFallbackRouter(): RouterInterface
    {
        return $this->getFactory()->createZedDevelopmentRouter();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function cacheWarmUp(): void
    {
        $this->getFactory()->createCache()->warmUp();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function warmUpBackofficeRouterCache(): void
    {
        $this->getFactory()->createBackofficeCacheWarmer()->warmUp();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function warmUpMerchantPortalRouterCache(): void
    {
        $this->getFactory()->createMerchantPortalCacheWarmer()->warmUp();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function warmUpBackendGatewayRouterCache(): void
    {
        $this->getFactory()->createBackendGatewayCacheWarmer()->warmUp();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RouterBundleCollectionTransfer
     */
    public function getRouterBundleCollection(): RouterBundleCollectionTransfer
    {
        return $this->getFactory()->createRouterReader()->getBundleCollection();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $bundle
     *
     * @return \Generated\Shared\Transfer\RouterControllerCollectionTransfer
     */
    public function getRouterControllerCollection(string $bundle): RouterControllerCollectionTransfer
    {
        return $this->getFactory()->createRouterReader()->getControllerCollection($bundle);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $bundle
     * @param string $controller
     *
     * @return \Generated\Shared\Transfer\RouterActionCollectionTransfer
     */
    public function getRouterActionCollection(string $bundle, string $controller): RouterActionCollectionTransfer
    {
        return $this->getFactory()->createRouterReader()->getActionCollection($bundle, $controller);
    }
}
