<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi\Processor\Builder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToOauthServiceInterface;
use Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractorInterface;
use Spryker\Glue\OauthBackendApi\Processor\Mapper\GlueRequestMapperInterface;

class RequestBuilder implements RequestBuilderInterface
{
    /**
     * @var int
     */
    protected const JWT_PAYLOAD_INDEX = 1;

    /**
     * @var \Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToOauthServiceInterface
     */
    protected OauthBackendApiToOauthServiceInterface $oauthService;

    /**
     * @var \Spryker\Glue\OauthBackendApi\Processor\Mapper\GlueRequestMapperInterface
     */
    protected GlueRequestMapperInterface $glueRequestMapper;

    /**
     * @var \Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractorInterface
     */
    protected AccessTokenExtractorInterface $accessTokenExtractor;

    /**
     * @param \Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToOauthServiceInterface $oauthService
     * @param \Spryker\Glue\OauthBackendApi\Processor\Mapper\GlueRequestMapperInterface $glueRequestMapper
     * @param \Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractorInterface $accessTokenExtractor
     */
    public function __construct(
        OauthBackendApiToOauthServiceInterface $oauthService,
        GlueRequestMapperInterface $glueRequestMapper,
        AccessTokenExtractorInterface $accessTokenExtractor
    ) {
        $this->oauthService = $oauthService;
        $this->glueRequestMapper = $glueRequestMapper;
        $this->accessTokenExtractor = $accessTokenExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildUserRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $oauthAccessTokenDataTransfer = $this->findUserByAccessToken($glueRequestTransfer);

        if (!$oauthAccessTokenDataTransfer || !$oauthAccessTokenDataTransfer->getOauthUserId()) {
            return $glueRequestTransfer;
        }

        return $this->glueRequestMapper->mapOauthAccessTokenDataTransferToGlueRequestTransfer($oauthAccessTokenDataTransfer, $glueRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OauthAccessTokenDataTransfer|null
     */
    protected function findUserByAccessToken(GlueRequestTransfer $glueRequestTransfer): ?OauthAccessTokenDataTransfer
    {
        $accessTokenData = $this->accessTokenExtractor->extract($glueRequestTransfer);
        if (!$accessTokenData) {
            return null;
        }

        $accessTokenPayload = $accessTokenData[static::JWT_PAYLOAD_INDEX] ?? null;
        if (!$accessTokenPayload) {
            return null;
        }

        return $this->oauthService->extractAccessTokenData($accessTokenPayload);
    }
}
