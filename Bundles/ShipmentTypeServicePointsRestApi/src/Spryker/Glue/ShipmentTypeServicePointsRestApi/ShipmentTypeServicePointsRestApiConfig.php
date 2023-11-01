<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypeServicePointsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig getSharedConfig()
 */
class ShipmentTypeServicePointsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig::RESOURCE_SERVICE_TYPES
     *
     * @api
     *
     * @var string
     */
    public const RESOURCE_SERVICE_TYPES = 'service-types';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_CODE_SERVICE_POINT_NOT_PROVIDED = '5601';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_CODE_SERVICE_POINT_ADDRESS_MISSING = '5602';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_CODE_CUSTOMER_DATA_MISSING = '5603';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_CODE_ITEM_SHIPPING_ADDRESS_MISSING = '5604';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_CODE_ONLY_ONE_SERVICE_POINT_SHOULD_BE_SELECTED = '5605';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_CODE_SERVICE_POINT_SHOULD_NOT_BE_PROVIDED = '5606';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_CODE_SERVICE_POINT_FOR_ITEM_SHOULD_NOT_BE_PROVIDED = '5607';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_DETAIL_SERVICE_POINT_NOT_PROVIDED = 'Please select service point.';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_DETAIL_SERVICE_POINT_SHOULD_NOT_BE_PROVIDED = 'Service Point can not be included for this type of delivery.';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_DETAIL_SERVICE_POINT_FOR_ITEM_SHOULD_NOT_BE_PROVIDED = 'Service Point for item "%s" can not be included with this type of delivery.';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_DETAIL_SERVICE_POINT_ADDRESS_MISSING = 'Service Point lacks an associated address. Service Point uuid: "%s"';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_DETAIL_CUSTOMER_DATA_MISSING = 'Required customer information is missing from the request body.';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_DETAIL_ITEM_SHIPPING_ADDRESS_MISSING = 'A shipping address is required for the selected shipment method and type. Group keys: "%s"';

    /**
     * @api
     *
     * @var string
     */
    public const ERROR_RESPONSE_DETAIL_ONLY_ONE_SERVICE_POINT_SHOULD_BE_SELECTED = 'For single shipments, only one Service Point can be included in the request body.';

    /**
     * Specification:
     * - Returns a list of shipment type keys which applicable for shipping address validation.
     *
     * @api
     *
     * @return list<string>
     */
    public function getApplicableShipmentTypeKeysForShippingAddress(): array
    {
        return $this->getSharedConfig()->getApplicableShipmentTypeKeysForShippingAddress();
    }
}
