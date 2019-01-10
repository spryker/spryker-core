<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Plugin\QuickOrder;

use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidatorPluginInterface;

/**
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageClientInterface getClient()
 * @method \Spryker\Client\ProductDiscontinuedStorage\ProductDiscontinuedStorageFactory getFactory()
 */
class QuickOrderDiscontinuedValidationPlugin extends AbstractPlugin implements QuickOrderValidatorPluginInterface
{
    /**
     * {@inheritdoc}
     * - Validate provided QuickOrderTransfer with discontinued validation.
     * - Returns the unchanged provided QuickOrderTransfer when no validation errors.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function validate(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        return $this->getFactory()
                    ->createDiscontinuedQuickOrderTransferValidator()
                    ->validateQuickOrder($quickOrderTransfer);
    }
}
