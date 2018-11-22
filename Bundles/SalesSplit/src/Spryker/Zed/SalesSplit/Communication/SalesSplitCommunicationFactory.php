<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesSplit\Communication;

use ArrayObject;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesSplit\Communication\Form\DataProvider\OrderItemSplitDataProvider;
use Spryker\Zed\SalesSplit\Communication\Form\OrderItemSplitForm;

/**
 * @method \Spryker\Zed\SalesSplit\SalesSplitConfig getConfig()
 * @method \Spryker\Zed\SalesSplit\Business\SalesSplitFacadeInterface getFacade()
 */
class SalesSplitCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createOrderItemSplitForm()
    {
        return $this->getFormFactory()->create(OrderItemSplitForm::class);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItems
     *
     * @return array
     */
    public function createOrderItemSplitFormCollection(ArrayObject $orderItems)
    {
        $formCollectionArray = [];
        $orderItemSplitDataProvider = $this->createOrderItemSplitDataProvider();
        foreach ($orderItems as $itemTransfer) {
            $formCollectionArray[$itemTransfer->getIdSalesOrderItem()] = $this
                ->getFormFactory()
                ->create(OrderItemSplitForm::class, $orderItemSplitDataProvider->getData($itemTransfer), $orderItemSplitDataProvider->getOptions())
                ->createView();
        }

        return $formCollectionArray;
    }

    /**
     * @return \Spryker\Zed\SalesSplit\Communication\Form\DataProvider\OrderItemSplitDataProvider
     */
    public function createOrderItemSplitDataProvider()
    {
        return new OrderItemSplitDataProvider();
    }
}
