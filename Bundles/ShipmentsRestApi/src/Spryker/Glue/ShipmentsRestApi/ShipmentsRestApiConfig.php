<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentsRestApi;

use Spryker\Glue\Kernel\AbstractBundleConfig;

class ShipmentsRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_SHIPMENT_METHODS = 'shipment-methods';

    public const ERROR_RESPONSE_CODE_SINGLE_MULTI_SHIPMENT_MIX = '4301';
    public const ERROR_RESPONSE_DETAIL_SINGLE_MULTI_SHIPMENT_MIX = 'Single and multiple shipments attributes are not allowed in the same request.';

    public const ERROR_RESPONSE_CODE_ADDRESS_NOT_VALID = '4302';
    public const ERROR_RESPONSE_DETAIL_ADDRESS_NOT_VALID = 'Provided address is not valid. You can either provide address ID or address fields.';
}
