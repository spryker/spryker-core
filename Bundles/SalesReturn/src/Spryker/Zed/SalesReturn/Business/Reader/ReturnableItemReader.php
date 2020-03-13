<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\ReturnableItemFilterTransfer;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface;
use Spryker\Zed\SalesReturn\SalesReturnConfig;

class ReturnableItemReader implements ReturnableItemReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\SalesReturn\SalesReturnConfig
     */
    protected $salesReturnConfig;

    /**
     * @var \Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnPolicyPluginInterface[]
     */
    protected $returnPolicyPlugins;

    /**
     * @param \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesReturn\SalesReturnConfig $salesReturnConfig
     * @param \Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnPolicyPluginInterface[] $returnPolicyPlugins
     */
    public function __construct(
        SalesReturnToSalesFacadeInterface $salesFacade,
        SalesReturnConfig $salesReturnConfig,
        array $returnPolicyPlugins
    ) {
        $this->salesFacade = $salesFacade;
        $this->salesReturnConfig = $salesReturnConfig;
        $this->returnPolicyPlugins = $returnPolicyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnableItemFilterTransfer $returnableItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getReturnableItems(ReturnableItemFilterTransfer $returnableItemFilterTransfer): ItemCollectionTransfer
    {
        $returnableItemFilterTransfer->requireCustomerReference();
        $orderItemFilterTransfer = $this->createOrderItemFilter($returnableItemFilterTransfer);

        $itemTransfers = $this->salesFacade
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();

        $itemTransfers = $this->executeReturnPolicyPlugins($itemTransfers);

        return (new ItemCollectionTransfer())
            ->setItems($itemTransfers);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function executeReturnPolicyPlugins(ArrayObject $itemTransfers): ArrayObject
    {
        foreach ($this->returnPolicyPlugins as $returnPolicyPlugin) {
            $itemTransfers = $returnPolicyPlugin->execute($itemTransfers);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnableItemFilterTransfer $returnableItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderItemFilterTransfer
     */
    protected function createOrderItemFilter(ReturnableItemFilterTransfer $returnableItemFilterTransfer): OrderItemFilterTransfer
    {
        return (new OrderItemFilterTransfer())
            ->fromArray($returnableItemFilterTransfer->toArray(), true)
            ->setItemStates($this->salesReturnConfig->getReturnableStateNames());
    }
}
