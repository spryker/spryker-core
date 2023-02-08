<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\WarehouseUsersBackendApi;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class WarehouseUsersBackendApiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Resource type for Warehouse User Assignments.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_TYPE_WAREHOUSE_USER_ASSIGNMENTS = 'warehouse-user-assignments';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND = '5201';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_USER_NOT_FOUND = '5202';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_WAREHOUSE_NOT_FOUND = '5203';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_OPERATION_IS_FORBIDDEN = '5204';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS = '5205';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_WAREHOUSE_USER_ASSIGNMENT_ALREADY_EXISTS = '5206';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAILS_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND = 'Warehouse user assignment not found.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAILS_USER_NOT_FOUND = 'User not found.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAILS_WAREHOUSE_NOT_FOUND = 'Warehouse not found.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAILS_OPERATION_IS_FORBIDDEN = 'Operation is forbidden.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAILS_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS = 'User has too many active warehouse assignments.';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_DETAILS_WAREHOUSE_USER_ASSIGNMENT_ALREADY_EXISTS = 'Warehouse user assignment already exists.';

    /**
     * @uses \Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentExistsValidatorRule::GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND = 'warehouse_user_assignment.validation.warehouse_user_assignment_not_found';

    /**
     * @uses \Spryker\Zed\WarehouseUser\Business\Validator\Rules\UserExistsValidatorRule::GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND = 'warehouse_user_assignment.validation.user_not_found';

    /**
     * @uses \Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseExistsValidatorRule::GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND = 'warehouse_user_assignment.validation.warehouse_not_found';

    /**
     * @uses \Spryker\Zed\WarehouseUser\Business\Validator\Rules\SingleActiveWarehouseUserAssignmentValidatorRule::GLOSSARY_KEY_VALIDATION_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS = 'warehouse_user_assignment.validation.too_many_active_warehouse_assignments';

    /**
     * @uses \Spryker\Zed\WarehouseUser\Business\Validator\Rules\WarehouseUserAssignmentAlreadyExistsValidatorRule::GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_ALREADY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_ALREADY_EXISTS = 'warehouse_user_assignment.validation.warehouse_user_assignment_already_exists';

    /**
     * Specification:
     * - Returns a map of glossary keys to REST Error data.
     *
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getValidationGlossaryKeyToRestErrorMapping(): array
    {
        return [
            static::GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::RESPONSE_DETAILS_WAREHOUSE_USER_ASSIGNMENT_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_USER_NOT_FOUND,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::RESPONSE_DETAILS_USER_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_VALIDATION_WAREHOUSE_NOT_FOUND => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_WAREHOUSE_NOT_FOUND,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::RESPONSE_DETAILS_WAREHOUSE_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_VALIDATION_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::RESPONSE_DETAILS_TOO_MANY_ACTIVE_WAREHOUSE_ASSIGNMENTS,
            ],
            static::GLOSSARY_KEY_VALIDATION_WAREHOUSE_USER_ASSIGNMENT_ALREADY_EXISTS => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_WAREHOUSE_USER_ASSIGNMENT_ALREADY_EXISTS,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::MESSAGE => static::RESPONSE_DETAILS_WAREHOUSE_USER_ASSIGNMENT_ALREADY_EXISTS,
            ],
        ];
    }
}
