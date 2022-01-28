<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Business\Filter;

use Generated\Shared\Transfer\ApiDataTransfer;
use Spryker\Zed\MerchantRelationshipApi\MerchantRelationshipApiConfig;

class MerchantRelationshipRequestFilter implements MerchantRelationshipRequestFilterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationshipApi\MerchantRelationshipApiConfig
     */
    protected $merchantRelationshipApiConfig;

    /**
     * @param \Spryker\Zed\MerchantRelationshipApi\MerchantRelationshipApiConfig $merchantRelationshipApiConfig
     */
    public function __construct(MerchantRelationshipApiConfig $merchantRelationshipApiConfig)
    {
        $this->merchantRelationshipApiConfig = $merchantRelationshipApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     * @param array<string> $disallowedProperties
     *
     * @return \Generated\Shared\Transfer\ApiDataTransfer
     */
    public function filterOutDisallowedProperties(ApiDataTransfer $apiDataTransfer, array $disallowedProperties = []): ApiDataTransfer
    {
        $allowedProperties = $this->merchantRelationshipApiConfig->getAllowedProperties();
        $allowedProperties = array_diff($allowedProperties, $disallowedProperties);

        $data = array_intersect_key($apiDataTransfer->getData(), array_flip($allowedProperties));

        return $apiDataTransfer->setData($data);
    }
}
