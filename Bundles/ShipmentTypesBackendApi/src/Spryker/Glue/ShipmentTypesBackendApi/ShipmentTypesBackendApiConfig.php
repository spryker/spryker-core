<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesBackendApi;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class ShipmentTypesBackendApiConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Defines shipment type resource name.
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_SHIPMENT_TYPES = 'shipment-types';

    /**
     * Specification:
     * - Defines error code for unknown error.
     *
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_UNKNOWN_ERROR = '5500';

    /**
     * Specification:
     * - Defines glossary key for shipment type not found error.
     *
     * @api
     *
     * @var string
     */
    public const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND = 'shipment_type.validation.shipment_type_entity_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_EXISTS = 'shipment_type.validation.shipment_type_key_exists';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_INVALID_LENGTH = 'shipment_type.validation.shipment_type_key_invalid_length';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_IS_NOT_UNIQUE = 'shipment_type.validation.shipment_type_key_is_not_unique';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_NAME_INVALID_LENGTH = 'shipment_type.validation.shipment_type_name_invalid_length';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST = 'shipment_type.validation.store_does_not_exist';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SHIPMENT_TYPE_ENTITY_NOT_FOUND = '5501';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SHIPMENT_TYPE_KEY_EXISTS = '5502';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SHIPMENT_TYPE_KEY_INVALID_LENGTH = '5503';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SHIPMENT_TYPE_KEY_IS_NOT_UNIQUE = '5504';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SHIPMENT_TYPE_NAME_INVALID_LENGTH = '5505';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_STORE_DOES_NOT_EXIST = '5506';

    /**
     * @var string
     */
    protected const DEFAULT_LOCALE_NAME = 'en_US';

    /**
     * Specification:
     * - Returns a map of glossary keys to REST error data.
     *
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getErrorDataIndexedByGlossaryKey(): array
    {
        return [
            static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND => [
                GlueErrorTransfer::STATUS => Response::HTTP_NOT_FOUND,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_SHIPMENT_TYPE_ENTITY_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_EXISTS => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_SHIPMENT_TYPE_KEY_EXISTS,
            ],
            static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_INVALID_LENGTH => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_SHIPMENT_TYPE_KEY_INVALID_LENGTH,
            ],
            static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_KEY_IS_NOT_UNIQUE => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_SHIPMENT_TYPE_KEY_IS_NOT_UNIQUE,
            ],
            static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_NAME_INVALID_LENGTH => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_SHIPMENT_TYPE_NAME_INVALID_LENGTH,
            ],
            static::GLOSSARY_KEY_VALIDATION_STORE_DOES_NOT_EXIST => [
                GlueErrorTransfer::STATUS => Response::HTTP_BAD_REQUEST,
                GlueErrorTransfer::CODE => static::RESPONSE_CODE_STORE_DOES_NOT_EXIST,
            ],
        ];
    }

    /**
     * Specification:
     * - Returns default locale name that will be used for translation of error messages.
     *
     * @api
     *
     * @return string
     */
    public function getDefaultLocaleName(): string
    {
        return static::DEFAULT_LOCALE_NAME;
    }
}
