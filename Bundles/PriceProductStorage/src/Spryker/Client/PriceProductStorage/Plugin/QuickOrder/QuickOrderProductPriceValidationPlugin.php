<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Plugin\QuickOrder;

use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidationPluginInterface;

/**
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface getClient()
 * @method \Spryker\Client\PriceProductStorage\PriceProductStorageFactory getFactory()
 */
class QuickOrderProductPriceValidationPlugin extends AbstractPlugin implements QuickOrderValidationPluginInterface
{
    /**
     * {@inheritdoc}
     * - Validate provided QuickOrderTransfer with price validation.
     * - Returns the unchanged provided QuickOrderTransfer when no validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function validateQuickOrderItemProduct(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        return $this->getFactory()
            ->createPriceProductQuickOrderValidator()
            ->validateQuickOrder($quickOrderTransfer);
    }
}
