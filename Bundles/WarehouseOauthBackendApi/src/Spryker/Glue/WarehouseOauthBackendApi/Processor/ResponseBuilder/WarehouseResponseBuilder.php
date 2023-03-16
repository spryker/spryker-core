<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseOauthBackendApi\Processor\ResponseBuilder;

use Generated\Shared\Transfer\ApiTokenResponseAttributesTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\OauthErrorTransfer;
use Generated\Shared\Transfer\OauthResponseTransfer;
use Spryker\Glue\WarehouseOauthBackendApi\WarehouseOauthBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class WarehouseResponseBuilder implements WarehouseResponseBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createForbiddenErrorResponse(): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_FORBIDDEN)
            ->addError((new GlueErrorTransfer())
                ->setMessage(WarehouseOauthBackendApiConfig::RESPONSE_DETAILS_OPERATION_IS_FORBIDDEN)
                ->setStatus(Response::HTTP_FORBIDDEN)
                ->setCode(WarehouseOauthBackendApiConfig::RESPONSE_CODE_OPERATION_IS_FORBIDDEN));
    }

    /**
     * @param \Generated\Shared\Transfer\OauthErrorTransfer $oauthErrorTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createOauthBadRequestErrorResponse(OauthErrorTransfer $oauthErrorTransfer): GlueResponseTransfer
    {
        $glueErrorTransfer = (new GlueErrorTransfer())->setMessage($oauthErrorTransfer->getMessage())
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setCode($oauthErrorTransfer->getErrorType());

        return (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_BAD_REQUEST)
            ->addError($glueErrorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OauthResponseTransfer $oauthResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createWarehouseTokenResponse(OauthResponseTransfer $oauthResponseTransfer): GlueResponseTransfer
    {
        $glueResponseTransfer = new GlueResponseTransfer();

        $apiTokenResponseAttributesTransfer = (new ApiTokenResponseAttributesTransfer())->fromArray(
            $oauthResponseTransfer->toArray(),
            true,
        );

        $resourceTransfer = (new GlueResourceTransfer())
            ->setType(WarehouseOauthBackendApiConfig::RESOURCE_TOKEN)
            ->setAttributes($apiTokenResponseAttributesTransfer);

        return $glueResponseTransfer->addResource($resourceTransfer);
    }
}
