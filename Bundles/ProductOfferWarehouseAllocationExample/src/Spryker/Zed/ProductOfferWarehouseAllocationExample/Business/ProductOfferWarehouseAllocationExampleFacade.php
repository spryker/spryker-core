<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferWarehouseAllocationExample\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferWarehouseAllocationExample\Business\ProductOfferWarehouseAllocationExampleBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferWarehouseAllocationExample\Persistence\ProductOfferWarehouseAllocationExampleRepositoryInterface getRepository()
 */
class ProductOfferWarehouseAllocationExampleFacade extends AbstractFacade implements ProductOfferWarehouseAllocationExampleFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function allocateSalesOrderWarehouse(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()->createWarehouseAllocator()->allocateSalesOrderWarehouse($orderTransfer);
    }
}
