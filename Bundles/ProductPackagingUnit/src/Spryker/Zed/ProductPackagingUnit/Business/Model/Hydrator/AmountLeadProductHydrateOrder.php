<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Model\Hydrator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductFacadeInterface;

class AmountLeadProductHydrateOrder implements AmountLeadProductHydrateOrderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductPackagingUnitToProductFacadeInterface $productFacade
    ) {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithAmountLeadProduct(OrderTransfer $orderTransfer): OrderTransfer
    {

    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItemEntity
     *
     * @return void
     */
    protected function setAmountLeadProduct(ItemTransfer $itemTransfer, SpySalesOrderItem $salesOrderItemEntity): void
    {
        if (!$salesOrderItemEntity->getAmountSku() || !$salesOrderItemEntity->getAmount()) {
            return;
        }

        $leadProductConcreteTransfer = $this->findProductConcreteBySku($salesOrderItemEntity->getAmountSku());
        if (!$leadProductConcreteTransfer) {
            return;
        }

        $itemTransfer->setAmountLeadProduct(
            $this->createProductPackagingLeadProductTransfer($leadProductConcreteTransfer)
        );
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    protected function findProductConcreteBySku(string $sku): ?ProductConcreteTransfer
    {
        $idProductConcrete = $this->productFacade->findProductConcreteIdBySku($sku);

        if (!$idProductConcrete) {
            return null;
        }

        return $this->productFacade->findProductConcreteById($idProductConcrete);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer
     */
    protected function createProductPackagingLeadProductTransfer(ProductConcreteTransfer $productConcreteTransfer): ProductPackagingLeadProductTransfer
    {
        return (new ProductPackagingLeadProductTransfer())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract())
            ->setProduct($productConcreteTransfer);
    }
}