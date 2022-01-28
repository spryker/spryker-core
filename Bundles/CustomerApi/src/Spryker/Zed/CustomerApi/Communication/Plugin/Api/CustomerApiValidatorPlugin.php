<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Communication\Plugin\Api;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\ApiExtension\Dependency\Plugin\ApiValidatorPluginInterface;
use Spryker\Zed\CustomerApi\CustomerApiConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomerApi\Business\CustomerApiFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerApi\CustomerApiConfig getConfig()
 * @method \Spryker\Zed\CustomerApi\Persistence\CustomerApiQueryContainerInterface getQueryContainer()
 */
class CustomerApiValidatorPlugin extends AbstractPlugin implements ApiValidatorPluginInterface
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
        return CustomerApiConfig::RESOURCE_CUSTOMERS;
    }

    /**
     * {@inheritDoc}
     * - Requires `ApiRequestTransfer.apiData` transfer property to be set.
     * - Validates the given API data and returns an array of errors if any occurs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    public function validate(ApiRequestTransfer $apiRequestTransfer): array
    {
        return $this->getFacade()->validate($apiRequestTransfer);
    }
}
