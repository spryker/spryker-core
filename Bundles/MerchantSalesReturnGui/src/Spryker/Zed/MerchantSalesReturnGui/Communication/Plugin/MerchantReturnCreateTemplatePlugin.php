<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication\Plugin;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateTemplatePluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesReturnGui\MerchantSalesReturnGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesReturnGui\Communication\MerchantSalesReturnGuiCommunicationFactory getFactory()
 */
class MerchantReturnCreateTemplatePlugin extends AbstractPlugin implements ReturnCreateTemplatePluginInterface
{
    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return '@MerchantSalesReturnGui/SalesReturn/Create/index.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return mixed[]
     */
    public function getTemplateData(OrderTransfer $orderTransfer): array
    {
        return [
            'order' => $orderTransfer,
            'merchants' => $this->getMerchants($orderTransfer),
            'indexedMerchantOrders' => $this->getMerchantOrders($orderTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    protected function getMerchants(OrderTransfer $orderTransfer): MerchantCollectionTransfer
    {
        $merchantCriteriaTransfer = new MerchantCriteriaTransfer();
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getMerchantReference()) {
                $merchantCriteriaTransfer->addMerchantReference($itemTransfer->getMerchantReference());
            }
        }

        return $this
            ->getFactory()
            ->getMerchantFacade()
            ->get($merchantCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function getMerchantOrders(OrderTransfer $orderTransfer): array
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdOrder($orderTransfer->getIdSalesOrder());

        $merchantOrderCollection = $this
            ->getFactory()
            ->getMerchantSalesOrderFacade()
            ->getMerchantOrderCollection($merchantOrderCriteriaTransfer);

        $indexedMerchantOrders = [];

        foreach ($merchantOrderCollection->getMerchantOrders() as $merchantOrderTransfer) {
            $indexedMerchantOrders[$merchantOrderTransfer->getMerchantReference()] = $merchantOrderTransfer;
        }

        return $indexedMerchantOrders;
    }
}
