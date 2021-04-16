<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication\Plugin\SalesReturnGui;

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
            'merchantOrders' => $this->getFactory()->createMerchantSalesReturnReader()->getMerchantOrders($orderTransfer),
        ];
    }
}
