<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Symfony\Component\HttpFoundation\Response;

class MultiFactorAuthResponseBuilder implements MultiFactorAuthResponseBuilderInterface
{
 /**
  * @return \Generated\Shared\Transfer\GlueResponseTransfer
  */
    public function createNoUserIdentifierErrorResponse(): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_BAD_REQUEST);
        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setCode(MultiFactorAuthConfig::RESPONSE_CODE_NO_USER_IDENTIFIER)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setMessage(MultiFactorAuthConfig::RESPONSE_DETAIL_NO_USER_IDENTIFIER);

        return $glueResponseTransfer->addError($glueErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createUserNotFoundResponse(): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_BAD_REQUEST);
        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setCode(MultiFactorAuthConfig::RESPONSE_USER_NOT_FOUND)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setMessage(MultiFactorAuthConfig::RESPONSE_DETAIL_USER_NOT_FOUND);

        return $glueResponseTransfer->addError($glueErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createMissingTypeErrorResponse(): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_BAD_REQUEST);
        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_TYPE_MISSING)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setMessage(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_TYPE_MISSING);

        return $glueResponseTransfer->addError($glueErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createNotFoundTypeErrorResponse(): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_BAD_REQUEST);
        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_TYPE_NOT_FOUND)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setMessage(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_TYPE_NOT_FOUND);

        return $glueResponseTransfer->addError($glueErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createMissingMultiFactorAuthCodeError(): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_FORBIDDEN);
        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_CODE_MISSING)
            ->setStatus(Response::HTTP_FORBIDDEN)
            ->setMessage(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_CODE_MISSING);

        return $glueResponseTransfer->addError($glueErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createInvalidMultiFactorAuthCodeError(): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_FORBIDDEN);
        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_CODE_INVALID)
            ->setStatus(Response::HTTP_FORBIDDEN)
            ->setMessage(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_CODE_INVALID);

        return $glueResponseTransfer->addError($glueErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createDeactivationFailedError(): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_BAD_REQUEST);
        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_DEACTIVATION_FAILED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setMessage(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_DEACTIVATION_FAILED);

        return $glueResponseTransfer->addError($glueErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createAlreadyActivatedMultiFactorAuthError(): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_BAD_REQUEST);
        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setCode(MultiFactorAuthConfig::ERROR_CODE_MULTI_FACTOR_AUTH_VERIFY_FAILED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setMessage(MultiFactorAuthConfig::ERROR_MESSAGE_MULTI_FACTOR_AUTH_VERIFY_FAILED);

        return $glueResponseTransfer->addError($glueErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createSendingCodeError(): GlueResponseTransfer
    {
        $glueResponseTransfer = (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_BAD_REQUEST);
        $glueErrorTransfer = (new GlueErrorTransfer())
            ->setCode(MultiFactorAuthConfig::RESPONSE_SENDING_CODE_ERROR)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setMessage(MultiFactorAuthConfig::ERROR_MESSAGE_SENDING_CODE_ERROR);

        return $glueResponseTransfer->addError($glueErrorTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createSuccessResponse(): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_NO_CONTENT);
    }
}
