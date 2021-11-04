<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ShipmentsRestApiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const RESOURCE_SHIPMENTS = 'shipments';

    /**
     * @var string
     */
    public const RESOURCE_SHIPMENT_METHODS = 'shipment-methods';

    /**
     * @var string
     */
    public const RESOURCE_ORDER_SHIPMENTS = 'order-shipments';

    /**
     * @var string
     */
    public const ERROR_RESPONSE_CODE_SINGLE_MULTI_SHIPMENT_MIX = '4301';

    /**
     * @var string
     */
    public const ERROR_RESPONSE_DETAIL_SINGLE_MULTI_SHIPMENT_MIX = 'Single and multiple shipments attributes are not allowed in the same request.';

    /**
     * @var string
     */
    public const ERROR_RESPONSE_CODE_ADDRESS_NOT_VALID = '4302';

    /**
     * @var string
     */
    public const ERROR_RESPONSE_DETAIL_ADDRESS_NOT_VALID = 'Provided address is not valid. You can either provide address ID or address fields.';

    /**
     * @var string
     */
    public const ERROR_RESPONSE_CODE_SHIPMENT_ATTRIBUTE_NOT_SPECIFIED = '4303';

    /**
     * @var string
     */
    public const ERROR_RESPONSE_DETAIL_SHIPMENT_ATTRIBUTE_NOT_SPECIFIED = 'Shipment attribute is not specified.';

    /**
     * @var string
     */
    public const ERROR_RESPONSE_CODE_SHIPMENTS_ATTRIBUTE_NOT_SPECIFIED = '4304';

    /**
     * @var string
     */
    public const ERROR_RESPONSE_DETAIL_SHIPMENTS_ATTRIBUTE_NOT_SPECIFIED = 'Shipments attributes are not specified.';
}
