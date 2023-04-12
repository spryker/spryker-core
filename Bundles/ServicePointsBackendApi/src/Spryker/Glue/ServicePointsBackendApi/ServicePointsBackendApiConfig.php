<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class ServicePointsBackendApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_SERVICE_POINTS = 'service-points';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointExistenceByUuidServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND
     *
     * @api
     *
     * @var string
     */
    public const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND = 'service_point.validation.service_point_entity_not_found';

    /**
     * @api
     *
     * @var string
     */
    public const GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY = 'service_point.validation.wrong_request_body';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_UNKNOWN_ERROR = '5400';

    /**
     * @api
     *
     * @var string
     */
    protected const RESPONSE_CODE_WRONG_REQUEST_BODY = '5401';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointKeyExistenceServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_EXISTS
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_EXISTS = 'service_point.validation.service_point_key_exists';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointKeyLengthServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_WRONG_LENGTH = 'service_point.validation.service_point_key_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointKeyUniquenessServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_IS_NOT_UNIQUE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_IS_NOT_UNIQUE = 'service_point.validation.service_point_key_is_not_unique';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointNameLengthServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_NAME_WRONG_LENGTH
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_NAME_WRONG_LENGTH = 'service_point.validation.service_point_name_wrong_length';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\StoreExistenceServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST
     *
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST = 'service_point.validation.store_does_not_exist';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_ENTITY_NOT_FOUND = '5403';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SERVICE_POINT_KEY_EXISTS = '5404';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SERVICE_POINT_KEY_WRONG_LENGTH = '5405';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SERVICE_POINT_KEY_IS_NOT_UNIQUE = '5406';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SERVICE_POINT_NAME_WRONG_LENGTH = '5407';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_STORE_DOES_NOT_EXIST = '5408';

    /**
     * Specification:
     * - Returns a map of glossary keys to REST error data.
     *
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getGlossaryKeyToErrorDataMapping(): array
    {
        return [
            static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_ENTITY_NOT_FOUND,
                GlueErrorTransfer::STATUS => Response::HTTP_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_VALIDATION_WRONG_REQUEST_BODY => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_WRONG_REQUEST_BODY,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_EXISTS => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_SERVICE_POINT_KEY_EXISTS,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_WRONG_LENGTH => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_SERVICE_POINT_KEY_WRONG_LENGTH,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_KEY_IS_NOT_UNIQUE => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_SERVICE_POINT_KEY_IS_NOT_UNIQUE,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_NAME_WRONG_LENGTH => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_SERVICE_POINT_NAME_WRONG_LENGTH,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
            static::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST => [
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_STORE_DOES_NOT_EXIST,
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
        ];
    }
}
