<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthMerchantUser\Business\Checker;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig;

class MerchantUserTypeOauthScopeAuthorizationChecker implements MerchantUserTypeOauthScopeAuthorizationCheckerInterface
{
    /**
     * @var string
     */
    protected const GLUE_REQUEST_USER = 'glueRequestUser';

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
    protected const METHODS = 'methods';

    /**
     * @var string
     */
    protected const IS_REGULAR_EXPRESSION = 'isRegularExpression';

    /**
     * @var \Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig
     */
    protected OauthMerchantUserConfig $oauthMerchantUserConfig;

    /**
     * @param \Spryker\Zed\OauthMerchantUser\OauthMerchantUserConfig $oauthMerchantUserConfig
     */
    public function __construct(OauthMerchantUserConfig $oauthMerchantUserConfig)
    {
        $this->oauthMerchantUserConfig = $oauthMerchantUserConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     *
     * @return bool
     */
    public function authorize(AuthorizationRequestTransfer $authorizationRequestTransfer): bool
    {
        $requestData = $authorizationRequestTransfer->getEntityOrFail()->getData();
        if (!$this->isAuthorizableRequest($requestData)) {
            return false;
        }

        if (!$this->isMerchantUserScopeProvided($requestData[static::GLUE_REQUEST_USER]->getScopes())) {
            return false;
        }

        return $this->isPathAllowed($requestData[static::PATH], $requestData[static::METHOD]);
    }

    /**
     * @param array<mixed> $requestData
     *
     * @return bool
     */
    protected function isAuthorizableRequest(array $requestData): bool
    {
        return !empty($requestData[static::GLUE_REQUEST_USER]) && isset($requestData[static::METHOD], $requestData[static::PATH]);
    }

    /**
     * @param string $path
     * @param string $method
     *
     * @return bool
     */
    protected function isPathAllowed(string $path, string $method): bool
    {
        $allowedPaths = $this->oauthMerchantUserConfig->getAllowedForMerchantUserPaths();

        if (!$allowedPaths) {
            return false;
        }

        return $this->isAllowedByPath($allowedPaths, $path, $method) || $this->isAllowedByRegularExpression($allowedPaths, $path, $method);
    }

    /**
     * @param array<string, mixed> $allowedPaths
     * @param string $path
     * @param string $method
     *
     * @return bool
     */
    protected function isAllowedByPath(
        array $allowedPaths,
        string $path,
        string $method
    ): bool {
        return array_key_exists($path, $allowedPaths) && $this->checkIfRouteAllowedForMethods($allowedPaths[$path], $method);
    }

    /**
     * @param array<string, mixed> $allowedPaths
     * @param string $method
     *
     * @return bool
     */
    protected function checkIfRouteAllowedForMethods(array $allowedPaths, string $method): bool
    {
        return !isset($allowedPaths[static::METHODS]) || in_array(strtolower($method), $allowedPaths[static::METHODS], true);
    }

    /**
     * @param array<string, mixed> $allowedPaths
     * @param string $path
     * @param string $method
     *
     * @return bool
     */
    protected function isAllowedByRegularExpression(
        array $allowedPaths,
        string $path,
        string $method
    ): bool {
        foreach ($allowedPaths as $allowedPathKey => $allowedPathData) {
            if ($allowedPathData[static::IS_REGULAR_EXPRESSION] !== true) {
                continue;
            }

            if (preg_match($allowedPathKey, $path) && $this->checkIfRouteAllowedForMethods($allowedPathData, $method)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<string> $authorizedOauthScopes
     *
     * @return bool
     */
    protected function isMerchantUserScopeProvided(array $authorizedOauthScopes): bool
    {
        return in_array($this->oauthMerchantUserConfig->getMerchantUserScope(), $authorizedOauthScopes, true);
    }
}
