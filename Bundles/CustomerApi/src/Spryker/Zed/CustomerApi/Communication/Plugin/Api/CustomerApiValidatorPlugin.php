<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Communication\Plugin\Api;

use Generated\Shared\Transfer\ApiDataTransfer;
use Spryker\Zed\Api\Dependency\Plugin\ApiValidatorPluginInterface;
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
    public function getResourceName()
    {
        return CustomerApiConfig::RESOURCE_CUSTOMERS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiValidationErrorTransfer[]
     */
    public function validate(ApiDataTransfer $apiDataTransfer)
    {
        return $this->getFacade()->validate($apiDataTransfer);
    }
}
