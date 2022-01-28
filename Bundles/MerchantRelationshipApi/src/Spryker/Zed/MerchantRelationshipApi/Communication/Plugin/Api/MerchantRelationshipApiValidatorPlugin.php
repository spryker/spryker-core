<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipApi\Communication\Plugin\Api;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\ApiExtension\Dependency\Plugin\ApiValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantRelationshipApi\MerchantRelationshipApiConfig;

/**
 * @method \Spryker\Zed\MerchantRelationshipApi\Business\MerchantRelationshipApiFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRelationshipApi\MerchantRelationshipApiConfig getConfig()
 */
class MerchantRelationshipApiValidatorPlugin extends AbstractPlugin implements ApiValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return MerchantRelationshipApiConfig::RESOURCE_MERCHANT_RELATIONSHIP;
    }

    /**
     * {@inheritDoc}
     * - Validates if all required fields are present in `ApiRequestTransfer.requestData`.
     * - Returns an array of errors if any occurs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    public function validate(ApiRequestTransfer $apiRequestTransfer): array
    {
        return $this->getFacade()->validateMerchantRelationshipRequestData($apiRequestTransfer);
    }
}
