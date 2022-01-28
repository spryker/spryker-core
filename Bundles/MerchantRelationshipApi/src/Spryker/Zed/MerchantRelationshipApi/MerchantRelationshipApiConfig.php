<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi;

use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\MerchantRelationshipApi\Business\Request\MerchantRelationshipRequestDataInterface;

class MerchantRelationshipApiConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const RESOURCE_MERCHANT_RELATIONSHIP = 'merchant-relationships';

    /**
     * Specification:
     * - Returns properties that must be ignored while merchant relationship updating.
     *
     * @api
     *
     * @return array<string>
     */
    public function getIgnoredPropertiesForUpdate(): array
    {
        return [
            MerchantRelationshipRequestDataInterface::KEY_MERCHANT_REFERENCE,
            MerchantRelationshipRequestDataInterface::KEY_ID_COMPANY,
        ];
    }

    /**
     * Specification:
     * - Returns request data fields that are allowed for POST and PATCH requests.
     *
     * @api
     *
     * @return array<string>
     */
    public function getAllowedProperties(): array
    {
        return [
            MerchantRelationshipRequestDataInterface::KEY_MERCHANT_REFERENCE,
            MerchantRelationshipRequestDataInterface::KEY_ID_COMPANY,
            MerchantRelationshipRequestDataInterface::KEY_ID_BUSINESS_UNIT_OWNER,
            MerchantRelationshipRequestDataInterface::KEY_ASSIGNED_BUSINESS_UNITS,
            MerchantRelationshipRequestDataInterface::KEY_ASSIGNED_PRODUCT_LISTS,
        ];
    }
}
