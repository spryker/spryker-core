<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class ServicePointsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_SERVICE_POINTS = 'service-points';

    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_SERVICE_POINT_ADDRESSES = 'service-point-addresses';

    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_SERVICE_TYPES = 'service-types';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePoint\ExistenceByUuidServicePointValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND
     *
     * @api
     *
     * @var string
     */
    public const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND = 'service_point.validation.service_point_entity_not_found';

    /**
     * @uses \Spryker\Zed\ServicePoint\Business\Validator\Rule\ServicePointAddress\ExistenceByUuidServicePointAddressValidatorRule::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ENTITY_NOT_FOUND
     *
     * @api
     *
     * @var string
     */
    public const GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ENTITY_NOT_FOUND = 'service_point.validation.service_point_address_entity_not_found';

    /**
     * @api
     *
     * @var string
     */
    public const GLOSSARY_KEY_ERROR_ENDPOINT_NOT_FOUND = 'service_points_rest_api.error.endpoint_not_found';

    /**
     * @api
     *
     * @var string
     */
    public const GLOSSARY_KEY_ERROR_SERVICE_POINT_IDENTIFIER_IS_NOT_SPECIFIED = 'service_points_rest_api.error.service_point_identifier_is_not_specified';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_UNKNOWN_ERROR = '5400';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SERVICE_POINT_ENTITY_NOT_FOUND = '5401';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SERVICE_POINT_ADDRESS_ENTITY_NOT_FOUND = '5402';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_ENDPOINT_NOT_FOUND = '5403';

    /**
     * @var string
     */
    protected const RESPONSE_CODE_SERVICE_POINT_IDENTIFIER_IS_NOT_SPECIFIED = '5404';

    /**
     * @uses \Spryker\Client\ServicePointSearch\ServicePointSearchConfig::SORT_CITY
     *
     * @var string
     */
    protected const SORT_FIELD_CITY = 'city';

    /**
     * @api
     *
     * @return list<string>
     */
    public function getAllowedSortFields(): array
    {
        return [
            static::SORT_FIELD_CITY,
        ];
    }

    /**
     * Specification:
     * - Returns a map of glossary keys to REST Error data.
     *
     * @api
     *
     * @return array<string, array<string, mixed>>
     */
    public function getGlossaryKeyToErrorDataMapping(): array
    {
        return [
            static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ENTITY_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SERVICE_POINT_ENTITY_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_VALIDATION_SERVICE_POINT_ADDRESS_ENTITY_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SERVICE_POINT_ADDRESS_ENTITY_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_ERROR_ENDPOINT_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_ENDPOINT_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
            ],
            static::GLOSSARY_KEY_ERROR_SERVICE_POINT_IDENTIFIER_IS_NOT_SPECIFIED => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SERVICE_POINT_IDENTIFIER_IS_NOT_SPECIFIED,
                RestErrorMessageTransfer::STATUS => Response::HTTP_BAD_REQUEST,
            ],
        ];
    }
}
