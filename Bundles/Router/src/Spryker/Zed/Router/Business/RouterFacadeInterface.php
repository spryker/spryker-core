<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business;

use Generated\Shared\Transfer\RouterActionCollectionTransfer;
use Generated\Shared\Transfer\RouterBundleCollectionTransfer;
use Generated\Shared\Transfer\RouterControllerCollectionTransfer;
use Spryker\Zed\Router\Business\Router\ChainRouter;
use Spryker\Zed\Router\Business\Router\RouterInterface;

/**
 * @method \Spryker\Zed\Router\Business\RouterBusinessFactory getFactory()
 */
interface RouterFacadeInterface
{
    /**
     * Specification:
     * - Returns a ChainRouter which is added to the Application.
     * - Executes {@link \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface} plugin stack to add Router to the ChainRouter.
     *
     * @api
     *
     * @internal
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
     * Specification:
     * - Returns a ChainRouter which is added to the BackendGateway Application.
     * - Executes {@link \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface} plugin stack to add Router to the ChainRouter.
     *
     * @api
     *
     * @internal
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
     * - Executes {@link \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface} plugin stack to add Router to the ChainRouter.
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
     * Specification:
     * - Returns Router which handles Zed routes.
     *
     * @api
     *
     * @internal
     *
     * @return \Spryker\Zed\Router\Business\Router\RouterInterface
     */
    public function getZedRouter(): RouterInterface;

    /**
     * Specification:
     * - Returns a ChainRouter which is added to the Application.
     * - Executes {@link \Spryker\Zed\RouterExtension\Dependency\Plugin\RouterPluginInterface} plugin stack to add Router to the ChainRouter.
     *
     * @api
     *
     * @internal
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
     * Specification:
     * - Builds the cache for the Router.
     *
     * @api
     *
     * @return void
     */
    public function cacheWarmUp(): void;

    /**
     * Specification:
     * - Gets route bundles.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\RouterBundleCollectionTransfer
     */
    public function getRouterBundleCollection(): RouterBundleCollectionTransfer;

    /**
     * Specification:
     * - Gets route bundle controllers.
     *
     * @api
     *
     * @param string $bundle
     *
     * @return \Generated\Shared\Transfer\RouterControllerCollectionTransfer
     */
    public function getRouterControllerCollection(string $bundle): RouterControllerCollectionTransfer;

    /**
     * Specification:
     * - Gets route bundle controller actions.
     *
     * @api
     *
     * @param string $bundle
     * @param string $controller
     *
     * @return \Generated\Shared\Transfer\RouterActionCollectionTransfer
     */
    public function getRouterActionCollection(string $bundle, string $controller): RouterActionCollectionTransfer;
}
