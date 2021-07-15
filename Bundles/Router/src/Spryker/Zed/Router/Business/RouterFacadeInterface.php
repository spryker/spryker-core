<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business;

use Spryker\Zed\Router\Business\Router\ChainRouter;
use Spryker\Zed\Router\Business\Router\RouterInterface;

/**
 * @method \Spryker\Zed\Router\Business\RouterBusinessFactory getFactory()
 */
interface RouterFacadeInterface
{
    /**
     * @api
     *
     * @internal
     *
     * Specification:
     * - Returns a ChainRouter which is added to the Application.
     * - Uses RouterExtensionPluginInterfaces to add Router to the ChainRouter.
     *
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getBackofficeChainRouter(): ChainRouter;

    /**
     * Specification:
     * - Returns a ChainRouter which is added to the MerchantPortal Application.
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getMerchantPortalChainRouter(): ChainRouter;

    /**
     * Specification:
     * - Returns Router which handles Backoffice routes.
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getBackofficeRouter(): RouterInterface;

    /**
     * @api
     *
     * @internal
     *
     * Specification:
     * - Returns a ChainRouter which is added to the BackendGateway Application.
     * - Uses RouterExtensionPluginInterfaces to add Router to the ChainRouter.
     *
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getBackendGatewayChainRouter(): ChainRouter;

    /**
     * Specification:
     * - Returns Router which handles MerchantPortal routes.
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getMerchantPortalRouter(): RouterInterface;

    /**
     * @api
     *
     * @internal
     *
     * Specification:
     * - Returns Router which handles BackendGateway routes.
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getBackendGatewayRouter(): RouterInterface;

    /**
     * Specification:
     * - Returns a ChainRouter which is added to the BackendApi Application.
     * - Uses RouterExtensionPluginInterfaces to add Router to the ChainRouter.
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getBackendApiChainRouter(): ChainRouter;

    /**
     * Specification:
     * - Builds the cache for the Backoffice Router.
     *
     * @api
     *
     * @return void
     */
    public function warmUpBackofficeRouterCache(): void;

    /**
     * Specification:
     * - Builds the cache for the MerchantPortal Router.
     *
     * @api
     *
     * @return void
     */
    public function warmUpMerchantPortalRouterCache(): void;

    /**
     * Specification:
     * - Builds the cache for the BackendGateway Router.
     *
     * @api
     *
     * @return void
     */
    public function warmUpBackendGatewayRouterCache(): void;

    /**
     * @api
     *
     * @internal
     *
     * @deprecated Use {@link \Spryker\Zed\Router\Business\RouterFacadeInterface::getBackofficeRouter()} instead.
     *
     * Specification:
     * - Returns Router which handles Zed routes.
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getZedRouter(): RouterInterface;

    /**
     * @api
     *
     * @internal
     *
     * @deprecated Use {@link \Spryker\Zed\Router\Business\RouterFacadeInterface::getBackofficeRouter()} instead.
     *
     * Specification:
     * - Returns a ChainRouter which is added to the Application.
     * - Uses RouterExtensionPluginInterfaces to add Router to the ChainRouter.
     *
     * @return \Spryker\Zed\Router\Business\Router\ChainRouter
     */
    public function getRouter(): ChainRouter;

    /**
     * Specification:
     * - Returns Router which handles Zed routes.
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getZedFallbackRouter(): RouterInterface;

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\Router\Business\RouterFacadeInterface::cacheWarmUpBackoffice()} instead.
     *
     * Specification:
     * - Builds the cache for the Router.
     *
     * @return void
     */
    public function cacheWarmUp(): void;
}
