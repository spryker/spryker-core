<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Builder\Route;

use Generated\Shared\Transfer\DynamicEntityConfigurationConditionsTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Controller\DynamicEntityBackendApiController;
use Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface;
use Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteBuilder implements RouteBuilderInterface
{
    /**
     * @var string
     */
    protected const ROUTE_COLLECTION_PATH_PLACEHOLDER = '/%s/%s';

    /**
     * @var string
     */
    protected const ROUTE_PATH_PLACEHOLDER = '/%s/%s/{id}';

    /**
     * @var string
     */
    protected const ROUTE_COLLECTION_NAME_PLACEHOLDER = '%sCollection%s';

    /**
     * @var string
     */
    protected const ROUTE_NAME_PLACEHOLDER = '%s%s';

    /**
     * @var string
     */
    protected const CONTROLLER = '_controller';

    /**
     * @var string
     */
    protected const METHOD = '_method';

    /**
     * @var string
     */
    protected const STRATEGIES_AUTHORIZATION = '_authorization_strategies';

    /**
     * @uses {@link \Spryker\Zed\ApiKeyAuthorizationConnector\Communication\Plugin\Authorization\ApiKeyAuthorizationStrategyPlugin::STRATEGY_NAME}
     *
     * @var string
     */
    protected const STRATEGY_AUTHORIZATION_API_KEY = 'ApiKey';

    /**
     * @var string
     */
    protected const GET_COLLECTION_ACTION = 'getCollectionAction';

    /**
     * @var string
     */
    protected const GET_ACTION = 'getAction';

    /**
     * @var string
     */
    protected const POST_ACTION = 'postAction';

    /**
     * @var string
     */
    protected const PATCH_ACTION = 'patchAction';

    /**
     * @var string
     */
    protected const PUT_ACTION = 'putAction';

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface
     */
    protected DynamicEntityBackendApiToDynamicEntityFacadeInterface $dynamicEntityFacade;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig
     */
    protected DynamicEntityBackendApiConfig $config;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\Dependency\Facade\DynamicEntityBackendApiToDynamicEntityFacadeInterface $dynamicEntityFacade
     * @param \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiConfig $config
     */
    public function __construct(
        DynamicEntityBackendApiToDynamicEntityFacadeInterface $dynamicEntityFacade,
        DynamicEntityBackendApiConfig $config
    ) {
        $this->dynamicEntityFacade = $dynamicEntityFacade;
        $this->config = $config;
    }

    /**
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    public function buildRouteCollection(RouteCollection $routeCollection): RouteCollection
    {
        $dynamicEntityConfigurationCollectionTransfer = $this->dynamicEntityFacade->getDynamicEntityConfigurationCollection(
            $this->createDynamicEntityConfigurationCriteriaTransfer(),
        );

        foreach ($dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations() as $dynamicEntityConfiguration) {
            $routeCollection = $this->addDynamicEntityRouteForGetCollection($dynamicEntityConfiguration, $routeCollection);
            $routeCollection = $this->addDynamicEntityRouteForGet($dynamicEntityConfiguration, $routeCollection);
            $routeCollection = $this->addDynamicEntityRouteForPost($dynamicEntityConfiguration, $routeCollection);
            $routeCollection = $this->addDynamicEntityRouteForPatchCollection($dynamicEntityConfiguration, $routeCollection);
            $routeCollection = $this->addDynamicEntityRouteForPatch($dynamicEntityConfiguration, $routeCollection);
            $routeCollection = $this->addDynamicEntityRouteForPutCollection($dynamicEntityConfiguration, $routeCollection);
            $routeCollection = $this->addDynamicEntityRouteForPut($dynamicEntityConfiguration, $routeCollection);
        }

        return $routeCollection;
    }

    /**
     * @param string $action
     * @param string $method
     * @param string $path
     *
     * @return \Symfony\Component\Routing\Route
     */
    protected function buildRoute(string $action, string $method, string $path): Route
    {
        $route = new Route($path);
        $route->setDefault(static::CONTROLLER, [DynamicEntityBackendApiController::class, $action])
            ->setDefault(static::METHOD, $method)
            ->setDefault(static::STRATEGIES_AUTHORIZATION, [static::STRATEGY_AUTHORIZATION_API_KEY])
            ->setMethods($method);

        return $route;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function addDynamicEntityRouteForGetCollection(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        RouteCollection $routeCollection
    ): RouteCollection {
        $route = $this->buildRoute(
            static::GET_COLLECTION_ACTION,
            Request::METHOD_GET,
            $this->formatPath(static::ROUTE_COLLECTION_PATH_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
        );

        $routeCollection->add(
            $this->formatName(static::ROUTE_COLLECTION_NAME_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail(), Request::METHOD_GET),
            $route,
        );

        return $routeCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function addDynamicEntityRouteForGet(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        RouteCollection $routeCollection
    ): RouteCollection {
        $route = $this->buildRoute(
            static::GET_ACTION,
            Request::METHOD_GET,
            $this->formatPath(static::ROUTE_PATH_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
        );

        $routeCollection->add(
            $this->formatName(static::ROUTE_NAME_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail(), Request::METHOD_GET),
            $route,
        );

        return $routeCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function addDynamicEntityRouteForPost(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        RouteCollection $routeCollection
    ): RouteCollection {
        $route = $this->buildRoute(
            static::POST_ACTION,
            Request::METHOD_POST,
            $this->formatPath(static::ROUTE_COLLECTION_PATH_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
        );

        $routeCollection->add(
            $this->formatName(static::ROUTE_NAME_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail(), Request::METHOD_POST),
            $route,
        );

        return $routeCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function addDynamicEntityRouteForPatch(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        RouteCollection $routeCollection
    ): RouteCollection {
        $route = $this->buildRoute(
            static::PATCH_ACTION,
            Request::METHOD_PATCH,
            $this->formatPath(static::ROUTE_PATH_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
        );

        $routeCollection->add(
            $this->formatName(static::ROUTE_NAME_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail(), Request::METHOD_PATCH),
            $route,
        );

        return $routeCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function addDynamicEntityRouteForPatchCollection(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        RouteCollection $routeCollection
    ): RouteCollection {
        $route = $this->buildRoute(
            static::PATCH_ACTION,
            Request::METHOD_PATCH,
            $this->formatPath(static::ROUTE_COLLECTION_PATH_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
        );

        $routeCollection->add(
            $this->formatName(static::ROUTE_COLLECTION_NAME_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail(), Request::METHOD_PATCH),
            $route,
        );

        return $routeCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function addDynamicEntityRouteForPutCollection(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        RouteCollection $routeCollection
    ): RouteCollection {
        $route = $this->buildRoute(
            static::PUT_ACTION,
            Request::METHOD_PUT,
            $this->formatPath(static::ROUTE_COLLECTION_PATH_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
        );

        $routeCollection->add(
            $this->formatName(static::ROUTE_COLLECTION_NAME_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail(), Request::METHOD_PUT),
            $route,
        );

        return $routeCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     * @param \Symfony\Component\Routing\RouteCollection $routeCollection
     *
     * @return \Symfony\Component\Routing\RouteCollection
     */
    protected function addDynamicEntityRouteForPut(
        DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer,
        RouteCollection $routeCollection
    ): RouteCollection {
        $route = $this->buildRoute(
            static::PUT_ACTION,
            Request::METHOD_PUT,
            $this->formatPath(static::ROUTE_PATH_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail()),
        );

        $routeCollection->add(
            $this->formatName(static::ROUTE_NAME_PLACEHOLDER, $dynamicEntityConfigurationTransfer->getTableAliasOrFail(), Request::METHOD_PUT),
            $route,
        );

        return $routeCollection;
    }

    /**
     * @param string $placeholder
     * @param string $tableAlias
     *
     * @return string
     */
    protected function formatPath(string $placeholder, string $tableAlias): string
    {
        return sprintf($placeholder, $this->config->getRoutePrefix(), $tableAlias);
    }

    /**
     * @param string $placeholder
     * @param string $tableAlias
     * @param string|null $method
     *
     * @return string
     */
    protected function formatName(string $placeholder, string $tableAlias, ?string $method = null): string
    {
        return sprintf($placeholder, $tableAlias, $method);
    }

    /**
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCriteriaTransfer
     */
    protected function createDynamicEntityConfigurationCriteriaTransfer(): DynamicEntityConfigurationCriteriaTransfer
    {
        $dynamicEntityConfigurationCriteriaTransfer = new DynamicEntityConfigurationCriteriaTransfer();
        $dynamicEntityConfigurationCriteriaTransfer->setDynamicEntityConfigurationConditions(
            (new DynamicEntityConfigurationConditionsTransfer())
                ->setIsActive(true),
        );

        return $dynamicEntityConfigurationCriteriaTransfer;
    }
}
