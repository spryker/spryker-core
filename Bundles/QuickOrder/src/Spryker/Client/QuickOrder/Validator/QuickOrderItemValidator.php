<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Validator;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderValidationResponseTransfer;

class QuickOrderItemValidator implements QuickOrderItemValidatorInterface
{
    /**
     * @var \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidatorPluginInterface[]
     */
    protected $quickOrderValidatorPlugins;

    /**
     * @param \Spryker\Client\QuickOrderExtension\Dependency\Plugin\QuickOrderValidatorPluginInterface[] $quickOrderValidatorPlugins
     */
    public function __construct(array $quickOrderValidatorPlugins)
    {
        $this->quickOrderValidatorPlugins = $quickOrderValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderValidationResponseTransfer
     */
    public function validate(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderValidationResponseTransfer
    {
        $resultValidationResponse = new QuickOrderValidationResponseTransfer();
        foreach ($this->quickOrderValidatorPlugins as $quickOrderValidationPlugin) {
            $validationPluginResponse = $quickOrderValidationPlugin->validateQuickOrderItem($quickOrderItemTransfer);
            $resultValidationResponse->fromArray($validationPluginResponse->modifiedToArray(), true);
        }

        return $resultValidationResponse;
    }
}
