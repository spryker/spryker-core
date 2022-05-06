<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi\Processor\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToOauthServiceInterface;
use Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToUtilEncodingServiceInterface;
use Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractorInterface;

class UserRequestBuilder implements UserRequestBuilderInterface
{
    /**
     * @var string
     */
    protected const KEY_USER_REFERENCE = 'user_reference';

    /**
     * @var string
     */
    protected const KEY_ID_USER = 'id_user';

    /**
     * @var \Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToOauthServiceInterface
     */
    protected $oauthService;

    /**
     * @var \Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractorInterface
     */
    protected $accessTokenExtractor;

    /**
     * @param \Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToOauthServiceInterface $oauthService
     * @param \Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Glue\OauthBackendApi\Processor\Extractor\AccessTokenExtractorInterface $accessTokenExtractor
     */
    public function __construct(
        OauthBackendApiToOauthServiceInterface $oauthService,
        OauthBackendApiToUtilEncodingServiceInterface $utilEncodingService,
        AccessTokenExtractorInterface $accessTokenExtractor
    ) {
        $this->oauthService = $oauthService;
        $this->utilEncodingService = $utilEncodingService;
        $this->accessTokenExtractor = $accessTokenExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildRequest(GlueRequestTransfer $glueRequestTransfer): GlueRequestTransfer
    {
        $oauthAccessTokenDataTransfer = $this->findUserByAccessToken($glueRequestTransfer);

        if (!$oauthAccessTokenDataTransfer || !$oauthAccessTokenDataTransfer->getOauthUserId()) {
            return $glueRequestTransfer;
        }

        return $this->mapRequestUserTransfer($oauthAccessTokenDataTransfer, $glueRequestTransfer);
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

        return $this->oauthService->extractAccessTokenData($accessTokenData[1]);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function mapRequestUserTransfer(
        OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueRequestTransfer {
        /** @var array<string, mixed> $userIdentifier */
        $userIdentifier = $this->utilEncodingService->decodeJson(
            $oauthAccessTokenDataTransfer->getOauthUserIdOrFail(),
            true,
        );

        if (!isset($userIdentifier[static::KEY_USER_REFERENCE]) && !isset($userIdentifier[static::KEY_ID_USER])) {
            return $glueRequestTransfer;
        }

        $glueRequestUserTransfer = (new GlueRequestUserTransfer())
            ->setNaturalIdentifier($userIdentifier[static::KEY_USER_REFERENCE])
            ->setSurrogateIdentifier($userIdentifier[static::KEY_ID_USER])
            ->setScopes($oauthAccessTokenDataTransfer->getOauthScopes());

        return $glueRequestTransfer->setRequestUser($glueRequestUserTransfer);
    }
}
