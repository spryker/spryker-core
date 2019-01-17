<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Plugin\QuickOrder;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderValidationResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidatorPluginInterface;

/**
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientInterface getClient()
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageFactory getFactory()
 */
class QuickOrderProductDiscontinuedValidatorPlugin extends AbstractPlugin implements QuickOrderValidatorPluginInterface
{
    /**
     * {@inheritdoc}
     * - Validate provided QuickOrderItemTransfer with discontinued validation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderValidationResponseTransfer
     */
    public function validateQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderValidationResponseTransfer
    {
        return $this->getFactory()
            ->createProductDiscontinuedQuickOrderValidator()
            ->validateQuickOrderItem($quickOrderItemTransfer);
    }
}
