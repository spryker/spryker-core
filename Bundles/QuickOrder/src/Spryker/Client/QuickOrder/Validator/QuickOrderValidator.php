<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Validator;

use Generated\Shared\Transfer\QuickOrderTransfer;

class QuickOrderValidator implements QuickOrderValidatorInterface
{
    /**
     * @var \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidationPluginInterface[]
     */
    protected $quickOrderValidationPlugins;

    /**
     * @param \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidationPluginInterface[] $quickOrderValidatorPlugins
     */
    public function __construct(array $quickOrderValidatorPlugins)
    {
        $this->quickOrderValidationPlugins = $quickOrderValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function validateQuickOrder(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        foreach ($this->quickOrderValidationPlugins as $quickOrderValidationPlugin) {
            $quickOrderTransfer = $quickOrderValidationPlugin->validateQuickOrderItemProduct($quickOrderTransfer);
        }

        return $quickOrderTransfer;
    }
}
