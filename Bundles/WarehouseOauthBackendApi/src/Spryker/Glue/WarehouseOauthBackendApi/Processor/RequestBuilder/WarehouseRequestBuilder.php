<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Processor\RequestBuilder;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestWarehouseTransfer;
use Generated\Shared\Transfer\OauthAccessTokenDataTransfer;
use Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToUtilEncodingServiceInterface;
use Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\GlueRequestReaderInterface;

class WarehouseRequestBuilder implements WarehouseRequestBuilderInterface
{
    /**
     * @var string
     */
    protected const USER_IDENTIFIER_ID_WAREHOUSE_KEY = 'id_warehouse';

    /**
     * @var \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToUtilEncodingServiceInterface
     */
    protected WarehouseOauthBackendApiToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @var \Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\GlueRequestReaderInterface
     */
    protected GlueRequestReaderInterface $glueRequestReader;

    /**
     * @param \Spryker\Glue\WarehouseOauthBackendApi\Dependency\Service\WarehouseOauthBackendApiToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Glue\WarehouseOauthBackendApi\Processor\Reader\GlueRequestReaderInterface $glueRequestReader
     */
    public function __construct(
        WarehouseOauthBackendApiToUtilEncodingServiceInterface $utilEncodingService,
        GlueRequestReaderInterface $glueRequestReader
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->glueRequestReader = $glueRequestReader;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    public function buildRequest(
        GlueRequestTransfer $glueRequestTransfer
    ): GlueRequestTransfer {
        $oauthAccessTokenDataTransfer = $this->glueRequestReader->findWarehouseByAccessToken($glueRequestTransfer);

        if (!$oauthAccessTokenDataTransfer || !$oauthAccessTokenDataTransfer->getOauthUserId()) {
            return $glueRequestTransfer;
        }

        return $this->expandWithWarehouse($glueRequestTransfer, $oauthAccessTokenDataTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestTransfer
     */
    protected function expandWithWarehouse(
        GlueRequestTransfer $glueRequestTransfer,
        OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
    ): GlueRequestTransfer {
        /** @var array<string, mixed> $userIdentifier */
        $userIdentifier = $this->utilEncodingService->decodeJson(
            $oauthAccessTokenDataTransfer->getOauthUserIdOrFail(),
            true,
        );

        if (!isset($userIdentifier[static::USER_IDENTIFIER_ID_WAREHOUSE_KEY])) {
            return $glueRequestTransfer;
        }

        $glueRequestWarehouseTransfer = $this->createGlueRequestWarehouseTransfer($userIdentifier, $oauthAccessTokenDataTransfer);

        return $glueRequestTransfer->setRequestWarehouse($glueRequestWarehouseTransfer);
    }

    /**
     * @param array<string, mixed> $userIdentifier
     * @param \Generated\Shared\Transfer\OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestWarehouseTransfer
     */
    protected function createGlueRequestWarehouseTransfer(
        array $userIdentifier,
        OauthAccessTokenDataTransfer $oauthAccessTokenDataTransfer
    ): GlueRequestWarehouseTransfer {
        return (new GlueRequestWarehouseTransfer())
            ->fromArray($userIdentifier, true)
            ->setIdWarehouse($userIdentifier[static::USER_IDENTIFIER_ID_WAREHOUSE_KEY])
            ->setScopes($oauthAccessTokenDataTransfer->getOauthScopes());
    }
}
