<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductWarehouseAllocationExample\Business;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductWarehouseAllocationExample\Business\ProductWarehouseAllocationExampleBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductWarehouseAllocationExample\Persistence\ProductWarehouseAllocationExampleRepositoryInterface getRepository()
 */
class ProductWarehouseAllocationExampleFacade extends AbstractFacade implements ProductWarehouseAllocationExampleFacadeInterface
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
        return $this->getFactory()->createSalesOrderWarehouseAllocator()->allocate($orderTransfer);
    }
}
