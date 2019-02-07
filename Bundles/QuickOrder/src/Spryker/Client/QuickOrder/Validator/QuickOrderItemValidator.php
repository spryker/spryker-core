<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Validator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ItemValidationTransfer;

class QuickOrderItemValidator implements QuickOrderItemValidatorInterface
{
    /**
     * @var \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ItemValidatorPluginInterface[]
     */
    protected $itemValidatorPlugins;

    /**
     * @param \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ItemValidatorPluginInterface[] $itemValidatorPlugins
     */
    public function __construct(array $itemValidatorPlugins)
    {
        $this->itemValidatorPlugins = $itemValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer
    {
        if (!$itemValidationTransfer->getSuggestedValues()) {
            $itemValidationTransfer->setSuggestedValues(new ItemTransfer());
        }

        foreach ($this->itemValidatorPlugins as $itemValidationPlugin) {
            $itemValidationTransfer = $itemValidationPlugin->validate($itemValidationTransfer);
        }

        return $itemValidationTransfer;
    }
}
