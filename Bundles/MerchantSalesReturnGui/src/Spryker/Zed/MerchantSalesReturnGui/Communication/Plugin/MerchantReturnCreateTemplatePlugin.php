<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication\Plugin;

use ArrayObject;
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
     * {@inheritDoc}
     * Specification:
     *  - Returns template path.
     *
     * @api
     *
     * @return string
     */
    public function getTemplatePath(): string
    {
        return '@MerchantSalesReturnGui/SalesReturn/Create/index.twig';
    }

    /**
     * {@inheritDoc}
     * Specification:
     *  - Returns merchant order data for return template.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return mixed[]
     */
    public function getTemplateData(OrderTransfer $orderTransfer): array
    {
        return [
            'merchantOrders' => $this->getMerchantOrders($orderTransfer),
        ];
    }

    /**
     * @phpstan-return \ArrayObject<int,\Generated\Shared\Transfer\MerchantOrderTransfer>
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\MerchantOrderTransfer[]
     */
    protected function getMerchantOrders(OrderTransfer $orderTransfer): ArrayObject
    {
        $merchantOrderCriteriaTransfer = (new MerchantOrderCriteriaTransfer())
            ->setIdOrder($orderTransfer->getIdSalesOrder())
            ->setWithMerchant(true);

        $merchantOrderCollection = $this->getFactory()
            ->getMerchantSalesOrderFacade()
            ->getMerchantOrderCollection($merchantOrderCriteriaTransfer);

        return $merchantOrderCollection->getMerchantOrders();
    }
}
