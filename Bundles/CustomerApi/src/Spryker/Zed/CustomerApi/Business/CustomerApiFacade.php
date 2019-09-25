<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CustomerApi\Business\CustomerApiBusinessFactory getFactory()
 */
class CustomerApiFacade extends AbstractFacade implements CustomerApiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function addCustomer(ApiDataTransfer $apiDataTransfer)
    {
        return $this->getFactory()
            ->createCustomerApi()
            ->add($apiDataTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer $customerTransfer
     */
    public function getCustomer($idCustomer)
    {
        return $this->getFactory()
            ->createCustomerApi()
            ->get($idCustomer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function updateCustomer($idCustomer, ApiDataTransfer $apiDataTransfer)
    {
        return $this->getFactory()
            ->createCustomerApi()
            ->update($idCustomer, $apiDataTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function removeCustomer($idCustomer)
    {
        return $this->getFactory()
            ->createCustomerApi()
            ->remove($idCustomer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function findCustomers(ApiRequestTransfer $apiRequestTransfer)
    {
        return $this->getFactory()
            ->createCustomerApi()
            ->find($apiRequestTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return array
     */
    public function validate(ApiDataTransfer $apiDataTransfer)
    {
        return $this->getFactory()
            ->createCustomerApiValidator()
            ->validate($apiDataTransfer);
    }
}
