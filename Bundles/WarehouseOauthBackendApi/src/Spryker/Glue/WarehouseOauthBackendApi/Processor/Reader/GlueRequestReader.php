<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToOauthServiceInterface;

class GlueRequestReader implements GlueRequestReaderInterface
{
    /**
     * @var int
     */
    protected const ACCESS_TOKEN_INDEX = 1;

    /**
     * @var string
     */
    protected const META_AUTHORIZATION_KEY = 'authorization';

    /**
     * @var \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToOauthServiceInterface
     */
    protected WarehouseOauthBackendApiToOauthServiceInterface $oauthService;

    /**
     * @param \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToOauthServiceInterface $oauthService
     */
    public function __construct(WarehouseOauthBackendApiToOauthServiceInterface $oauthService)
    {
        $this->oauthService = $oauthService;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenDataTransfer|null
     */
    public function findWarehouseByAccessToken(GlueRequestTransfer $glueRequestTransfer): ?OauthAccessTokenDataTransfer
    {
        $accessTokenData = $this->extract($glueRequestTransfer);
        $accessToken = $accessTokenData[static::ACCESS_TOKEN_INDEX] ?? null;
        if (!$accessToken) {
            return null;
        }

        return $this->oauthService->extractAccessTokenData($accessToken);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return list<string>|null
     */
    protected function extract(GlueRequestTransfer $glueRequestTransfer): ?array
    {
        if (!$glueRequestTransfer->getMeta()) {
            return null;
        }

        $authorizationToken = $glueRequestTransfer->getMeta()[static::META_AUTHORIZATION_KEY][0] ?? null;

        if (!$authorizationToken) {
            return null;
        }

        return $this->extractTokenData($authorizationToken);
    }

    /**
     * @param string $authorizationToken
     *
     * @return list<string>|null
     */
    protected function extractTokenData(string $authorizationToken): ?array
    {
        $result = preg_split('/\s+/', $authorizationToken);
        if ($result === false || !isset($result[1])) {
            return null;
        }

        return $result;
    }
}
