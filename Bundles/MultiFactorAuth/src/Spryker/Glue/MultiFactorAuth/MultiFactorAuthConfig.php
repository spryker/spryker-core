<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MultiFactorAuth;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;

class MultiFactorAuthConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_MULTI_FACTOR_AUTH_TYPES = 'multi-factor-auth-types';

    /**
     * @var string
     */
    public const RESOURCE_MULTI_FACTOR_AUTH_TRIGGER = 'multi-factor-auth-trigger';

    /**
     * @var string
     */
    public const RESOURCE_MULTI_FACTOR_AUTH_TYPE_ACTIVATE = 'multi-factor-auth-type-activate';

    /**
     * @var string
     */
    public const RESOURCE_MULTI_FACTOR_AUTH_TYPE_VERIFY = 'multi-factor-auth-type-verify';

    /**
     * @var string
     */
    public const RESOURCE_MULTI_FACTOR_AUTH_TYPE_DEACTIVATE = 'multi-factor-auth-type-deactivate';

    /**
     * @var string
     */
    public const CONTROLLER_MULTI_FACTOR_AUTH_TYPES = 'multi-factor-auth-types-resource';

    /**
     * @var string
     */
    public const CONTROLLER_MULTI_FACTOR_AUTH_TRIGGER = 'multi-factor-auth-trigger-resource';

    /**
     * @var string
     */
    public const CONTROLLER_MULTI_FACTOR_AUTH_TYPE_ACTIVATE = 'multi-factor-auth-type-activate-resource';

    /**
     * @var string
     */
    public const CONTROLLER_MULTI_FACTOR_AUTH_TYPE_VERIFY = 'multi-factor-auth-type-verify-resource';

    /**
     * @var string
     */
    public const CONTROLLER_MULTI_FACTOR_AUTH_TYPE_DEACTIVATE = 'multi-factor-auth-type-deactivate-resource';

    /**
     * @var string
     */
    public const HEADER_MULTI_FACTOR_AUTH_CODE = 'X-MFA-Code';

    /**
     * @var string
     */
    public const ERROR_CODE_MULTI_FACTOR_AUTH_CODE_MISSING = '5900';

    /**
     * @var string
     */
    public const ERROR_CODE_MULTI_FACTOR_AUTH_CODE_INVALID = '5901';

    /**
     * @var string
     */
    public const ERROR_CODE_MULTI_FACTOR_AUTH_TYPE_MISSING = '5902';

    /**
     * @var string
     */
    public const ERROR_CODE_MULTI_FACTOR_AUTH_DEACTIVATION_FAILED = '5903';

    /**
     * @var string
     */
    public const ERROR_CODE_MULTI_FACTOR_AUTH_VERIFY_FAILED = '5904';

    /**
     * @var string
     */
    public const RESPONSE_CODE_NO_CUSTOMER_IDENTIFIER = '5905';

    /**
     * @var string
     */
    public const ERROR_CODE_MULTI_FACTOR_AUTH_TYPE_NOT_FOUND = '5906';

    /**
     * @var string
     */
    public const RESPONSE_CUSTOMER_NOT_FOUND = '5907';

    /**
     * @var string
     */
    public const RESPONSE_CODE_NO_USER_IDENTIFIER = '5908';

    /**
     * @var string
     */
    public const RESPONSE_USER_NOT_FOUND = '5909';

    /**
     * @var string
     */
    public const RESPONSE_SENDING_CODE_ERROR = '5910';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_MULTI_FACTOR_AUTH_CODE_MISSING = 'X-MFA-Code header is missing.';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_MULTI_FACTOR_AUTH_CODE_INVALID = 'X-MFA-Code is invalid.';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_MULTI_FACTOR_AUTH_TYPE_MISSING = 'Multi-factor authentication type is missing.';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_MULTI_FACTOR_AUTH_DEACTIVATION_FAILED = 'Failed to deactivate multi-factor authentication.';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_MULTI_FACTOR_AUTH_VERIFY_FAILED = 'Multi-factor authentication type already activated.';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_NO_CUSTOMER_IDENTIFIER = 'No customer identifier provided.';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_MULTI_FACTOR_AUTH_TYPE_NOT_FOUND = 'Multi-factor authentication type is not found.';

    /**
     * @var string
     */
    public const ERROR_MESSAGE_SENDING_CODE_ERROR = 'Something went wrong while sending your code. Please try again later or contact the system administrator.';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_CUSTOMER_NOT_FOUND = 'Customer not found.';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_NO_USER_IDENTIFIER = 'No user identifier provided.';

    /**
     * @var string
     */
    public const RESPONSE_DETAIL_USER_NOT_FOUND = 'User not found.';

    /**
     * Specification:
     * - Returns a list of enabled resources for the multi-factor authentication in the following format:
     * [
     *    'resource-name',
     * ]
     *
     * @api
     *
     * @return array<string>
     */
    public function getRestApiMultiFactorAuthProtectedResources(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns a list of enabled backend resources for the multi-factor authentication in the following format:
     * [
     *    'resource-name',
     * ]
     *
     * @api
     *
     * @return array<string>
     */
    public function getMultiFactorAuthProtectedBackendResources(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns a list of enabled storefront resources for the multi-factor authentication in the following format:
     * [
     *    'resource-name',
     * ]
     *
     * @api
     *
     * @return array<string>
     */
    public function getMultiFactorAuthProtectedStorefrontResources(): array
    {
        return [];
    }

    /**
     * Specification:
     * - Returns a list of multi-factor authentication type statuses with their descriptions.
     *
     * @api
     *
     * @return array<int, string>
     */
    public function getMultiFactorAuthTypeStatuses(): array
    {
        return [
            MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION => 'activation is pending',
            MultiFactorAuthConstants::STATUS_ACTIVE => 'activated',
            MultiFactorAuthConstants::STATUS_INACTIVE => 'deactivated',
        ];
    }
}
