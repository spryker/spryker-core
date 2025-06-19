<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\StorefrontApi\ResponseBuilder;

use Generated\Shared\Transfer\GlueResponseTransfer;

interface MultiFactorAuthResponseBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createNoCustomerIdentifierErrorResponse(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createCustomerNotFoundResponse(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createMissingTypeErrorResponse(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createNotFoundTypeErrorResponse(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createMissingMultiFactorAuthCodeError(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createInvalidMultiFactorAuthCodeError(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createDeactivationFailedError(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createAlreadyActivatedMultiFactorAuthError(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createSendingCodeError(): GlueResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createSuccessResponse(): GlueResponseTransfer;
}
