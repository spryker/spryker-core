<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnGui\Communication\Plugin;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateTemplatePluginInterface;

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
     * @return array
     */
    public function getTemplateData(OrderTransfer $orderTransfer): array
    {
        // TODO: Implement getTemplateData() method.
    }
}
