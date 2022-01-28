<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ApiRequestTransfer;

/**
 * Implement this plugin to add a validation to your endpoint.
 */
interface ApiValidatorPluginInterface
{
    /**
     * Specification:
     * - Returns the resource name.
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string;

    /**
     * Specification:
     * - Validates request data.
     * - Returns an array of validation errors in case errors occur.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    public function validate(ApiRequestTransfer $apiRequestTransfer): array;
}
