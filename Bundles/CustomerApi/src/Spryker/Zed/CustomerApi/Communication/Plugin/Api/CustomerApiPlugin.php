<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Communication\Plugin\Api;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiFilterTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Shared\CustomerApi\CustomerApiConstants;
use Spryker\Shared\ProductApi\ProductApiConstants;
use Spryker\Zed\Api\Dependency\Plugin\ApiPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CustomerApi\Business\CustomerApiFacade getFacade()
 * @method \Spryker\Zed\Customer\Communication\CustomerCommunicationFactory getFactory()
 */
class CustomerApiPlugin extends AbstractPlugin implements ApiPluginInterface
{

    /**
     * @api
     *
     * @return string
     */
    public function getResourceType()
    {
        return CustomerApiConstants::RESOURCE_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        return $this->getFacade()->findCustomers($apiRequestTransfer);
    }

    /**
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ApiFilterTransfer $apiFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get($idCustomer, ApiFilterTransfer $apiFilterTransfer)
    {
        return $this->getFacade()->getCustomer($idCustomer, $apiFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer)
    {
        return $this->getFacade()->addCustomer($apiDataTransfer);
    }

    /**
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function update($idCustomer, ApiDataTransfer $apiDataTransfer)
    {
        return $this->getFacade()->updateCustomer($idCustomer, $apiDataTransfer);
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function delete($idCustomer)
    {
        return $this->getFacade()->deleteCustomer($idCustomer);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getType()
    {
        return ProductApiConstants::RESOURCE_TYPE;
    }

}
