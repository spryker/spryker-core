<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\Processor\ProtectedPathAuthorization\Checker;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorConfig;

class ProtectedPathAuthorizationChecker implements ProtectedPathAuthorizationCheckerInterface
{
    /**
     * @var string
     */
    protected const METHOD = 'method';

    /**
     * @var string
     */
    protected const PATH = 'path';

    /**
     * @var string
     */
    protected const GLUE_REQUEST_CUSTOMER = 'glueRequestCustomer';

    /**
     * @var string
     */
    protected const METHODS = 'methods';

    /**
     * @var string
     */
    protected const IS_REGULAR_EXPRESSION = 'isRegularExpression';

    /**
     * @var \Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorConfig
     */
    protected $glueStorefrontApiApplicationAuthorizationConfig;

    /**
     * @param \Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\GlueStorefrontApiApplicationAuthorizationConnectorConfig $glueStorefrontApiApplicationAuthorizationConfig
     */
    public function __construct(GlueStorefrontApiApplicationAuthorizationConnectorConfig $glueStorefrontApiApplicationAuthorizationConfig)
    {
        $this->glueStorefrontApiApplicationAuthorizationConfig = $glueStorefrontApiApplicationAuthorizationConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        $requestData = $authorizationRequestTransfer->getEntityOrFail()->getData();
        if (!isset($requestData[static::METHOD]) || !isset($requestData[static::PATH])) {
            return false;
        }
        $authorizationRequestTransfer->setPath($requestData[static::PATH]);
        $authorizationRequestTransfer->setMethod($requestData[static::METHOD]);

        $routeTransfer = (new RouteTransfer())
            ->setRoute($requestData[static::PATH])
            ->setMethod($requestData[static::METHOD]);

        if (!$this->isProtected($routeTransfer)) {
            return true;
        }

        return isset($requestData[static::GLUE_REQUEST_CUSTOMER]);
    }

    /**
     * @param \Generated\Shared\Transfer\RouteTransfer $routeTransfer
     *
     * @return bool
     */
    public function isProtected(RouteTransfer $routeTransfer): bool
    {
        $protectedPaths = $this->glueStorefrontApiApplicationAuthorizationConfig->getProtectedPaths();
        if ($protectedPaths === []) {
            return false;
        }
        $path = $routeTransfer->getRouteOrFail();
        $method = $routeTransfer->getMethodOrFail();

        if ($this->isProtectedByPath($protectedPaths, $path, $method)) {
            return true;
        }

        if ($this->isProtectedByRegularExpression($protectedPaths, $path, $method)) {
            return true;
        }

        return false;
    }

    /**
     * @param array<string, mixed> $protectedPaths
     * @param string $path
     * @param string $method
     *
     * @return bool
     */
    protected function isProtectedByPath(
        array $protectedPaths,
        string $path,
        string $method
    ): bool {
        if (
            array_key_exists($path, $protectedPaths) &&
            $this->checkIfRouteProtectedForMethods($protectedPaths[$path], $method)
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param array<string, mixed> $protectedPath
     * @param string $method
     *
     * @return bool
     */
    protected function checkIfRouteProtectedForMethods(array $protectedPath, string $method): bool
    {
        if (!isset($protectedPath[static::METHODS])) {
            return true;
        }

        if (in_array(strtolower($method), $protectedPath[static::METHODS])) {
            return true;
        }

        return false;
    }

    /**
     * @param array<string, mixed> $protectedPaths
     * @param string $path
     * @param string $method
     *
     * @return bool
     */
    protected function isProtectedByRegularExpression(
        array $protectedPaths,
        string $path,
        string $method
    ): bool {
        foreach ($protectedPaths as $protectedPathKey => $protectedPathData) {
            if (
                $protectedPathData[static::IS_REGULAR_EXPRESSION] === true
                && preg_match($protectedPathKey, $path)
                && $this->checkIfRouteProtectedForMethods($protectedPathData, $method)
            ) {
                return true;
            }
        }

        return false;
    }
}
