<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui\Communication\Plugin\Sales;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderItemsTableExpanderPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesOrderGui\Communication\MerchantSalesOrderGuiCommunicationFactory getFactory()
 */
class MerchantNameOrderItemsTableExpanderPlugin extends AbstractPlugin implements OrderItemsTableExpanderPluginInterface
{
    /**
     * @var string[]
     */
    protected $merchants;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getColumnName(): string
    {
        return 'Merchant';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function getColumnCellContent(ItemTransfer $itemTransfer): string
    {
        if ($itemTransfer->getMerchantReference() === null) {
            return '';
        }

        if (!isset($this->merchants[$itemTransfer->getMerchantReference()])) {
            $merchantTransfer = $this->getFactory()->getMerchantFacade()->findOne(
                (new MerchantCriteriaTransfer())->setMerchantReference($itemTransfer->getMerchantReference())
            );

            $this->merchants[$itemTransfer->getMerchantReference()] = $merchantTransfer ? $merchantTransfer->getName() : '';
        }

        return $this->merchants[$itemTransfer->getMerchantReference()];
    }
}
