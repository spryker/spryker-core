<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Response;

class ShipmentTypesRestApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_SHIPMENT_TYPES = 'shipment-types';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_UNKNOWN_ERROR = '5500';

    /**
     * @api
     *
     * @var string
     */
    public const RESPONSE_CODE_SHIPMENT_TYPE_ENTITY_NOT_FOUND = '5501';

    /**
     * @api
     *
     * @uses \Spryker\Zed\ShipmentType\Business\Validator\Rule\ShipmentTypeExistsShipmentTypeValidatorRule::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND
     *
     * @var string
     */
    public const GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND = 'shipment_type.validation.shipment_type_entity_not_found';

    /**
     * @api
     *
     * @return array<string, array<string, int|string>>
     */
    public function getGlossaryKeyToErrorDataMapping(): array
    {
        return [
            static::GLOSSARY_KEY_VALIDATION_SHIPMENT_TYPE_ENTITY_NOT_FOUND => [
                RestErrorMessageTransfer::CODE => static::RESPONSE_CODE_SHIPMENT_TYPE_ENTITY_NOT_FOUND,
                RestErrorMessageTransfer::STATUS => Response::HTTP_NOT_FOUND,
            ],
        ];
    }
}
