<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Validator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ItemValidationResponseTransfer;

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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationResponseTransfer
     */
    public function validate(ItemTransfer $itemTransfer): ItemValidationResponseTransfer
    {
        $resultValidationResponse = new ItemValidationResponseTransfer();
        foreach ($this->itemValidatorPlugins as $itemValidationPlugin) {
            $validationPluginResponse = $itemValidationPlugin->validate($itemTransfer);
            $resultValidationResponse = $this->processValidationMessages($validationPluginResponse, $resultValidationResponse);
            $resultValidationResponse = $this->processRecommendedValues($validationPluginResponse, $resultValidationResponse);
        }

        return $resultValidationResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemValidationResponseTransfer $validationPluginResponse
     * @param \Generated\Shared\Transfer\ItemValidationResponseTransfer $resultValidationResponse
     *
     * @return \Generated\Shared\Transfer\ItemValidationResponseTransfer
     */
    protected function processValidationMessages(ItemValidationResponseTransfer $validationPluginResponse, ItemValidationResponseTransfer $resultValidationResponse): ItemValidationResponseTransfer
    {
        if ($validationPluginResponse->getMessages()->count() === 0) {
            return $resultValidationResponse;
        }

        foreach ($validationPluginResponse->getMessages() as $messageTransfer) {
            $resultValidationResponse->addMessage($messageTransfer);
        }

        return $resultValidationResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemValidationResponseTransfer $validationPluginResponse
     * @param \Generated\Shared\Transfer\ItemValidationResponseTransfer $resultValidationResponse
     *
     * @return \Generated\Shared\Transfer\ItemValidationResponseTransfer
     */
    protected function processRecommendedValues(ItemValidationResponseTransfer $validationPluginResponse, ItemValidationResponseTransfer $resultValidationResponse): ItemValidationResponseTransfer
    {
        if ($validationPluginResponse->getRecommendedValues() === null) {
            return $resultValidationResponse;
        }

        $recommendedValues = $validationPluginResponse->getRecommendedValues()->modifiedToArray();
        $itemTransfer = $resultValidationResponse->getRecommendedValues() ?: new ItemTransfer();
        $itemTransfer->fromArray($recommendedValues);

        $resultValidationResponse->setRecommendedValues($itemTransfer);

        return $resultValidationResponse;
    }
}
