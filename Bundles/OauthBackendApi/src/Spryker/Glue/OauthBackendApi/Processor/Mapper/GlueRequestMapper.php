<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\OauthBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestUserTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToUtilEncodingServiceInterface;

class GlueRequestMapper implements GlueRequestMapperInterface
{
    /**
     * @var string
     */
    protected const KEY_USER_UUID = 'uuid';

    /**
     * @var string
     */
    protected const KEY_ID_USER = 'id_user';

    /**
     * @var \Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToUtilEncodingServiceInterface
     */
    protected OauthBackendApiToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Glue\OauthBackendApi\Dependency\Service\OauthBackendApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(OauthBackendApiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function mapOauthAccessTokenDataTransferToGlueRequestTransfer(
        OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueRequestTransfer {
        /** @var array<string, mixed> $userIdentifier */
        $userIdentifier = $this->utilEncodingService->decodeJson(
            $oauthAccessTokenDataTransfer->getOauthUserIdOrFail(),
            true,
        );

        if (!isset($userIdentifier[static::KEY_ID_USER])) {
            return $glueRequestTransfer;
        }

        $glueRequestUserTransfer = (new GlueRequestUserTransfer())
            ->setNaturalIdentifier($userIdentifier[static::KEY_USER_UUID] ?? null)
            ->setSurrogateIdentifier($userIdentifier[static::KEY_ID_USER])
            ->setScopes($oauthAccessTokenDataTransfer->getOauthScopes());

        return $glueRequestTransfer->setRequestUser($glueRequestUserTransfer);
    }
}
