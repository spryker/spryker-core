<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\GuiTableEditableDataErrorTransfer;
use Generated\Shared\Transfer\GuiTableEditableInitialDataTransfer;
use Symfony\Component\Form\FormErrorIterator;

class ProductAttributesMapper implements ProductAttributesMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormErrorIterator $errors
     * @param array $attributesInitialData
     *
     * @return string[][]
     */
    public function mapErrorsToAttributesData(FormErrorIterator $errors, array $attributesInitialData): array
    {
        /** @var \Symfony\Component\Form\FormError $error */
        foreach ($errors as $error) {
            if (!method_exists($error, 'getMessageParameters')) {
                continue;
            }
            $messageParameters = $error->getMessageParameters();
            $attributesRowNumber = $messageParameters['attributesRowNumber'] ?? null;

            if ($attributesRowNumber !== null) {
                $attributesInitialData[GuiTableEditableInitialDataTransfer::ERRORS][$attributesRowNumber][GuiTableEditableDataErrorTransfer::ROW_ERROR] = $error->getMessage();
            }
        }

        if (isset($attributesInitialData[GuiTableEditableInitialDataTransfer::ERRORS])) {
            $attributesInitialData[GuiTableEditableInitialDataTransfer::ERRORS] = $this->fillNotExistingNumericArrayElements(
                $attributesInitialData[GuiTableEditableInitialDataTransfer::ERRORS]
            );
        }

        return $attributesInitialData;
    }

    /**
     * @param array $attributesTableInitialData
     * @param array $data
     *
     * @return array
     */
    protected function fillNotExistingNumericArrayElements(array $attributesTableInitialData, $data = []): array
    {
        if (!$attributesTableInitialData) {
            return $attributesTableInitialData;
        }

        $keys = array_keys($attributesTableInitialData);

        $max = max($keys);

        for ($index = 0; $index < $max; $index++) {
            if (!isset($attributesTableInitialData[$index])) {
                $attributesTableInitialData[$index] = $data;
            }
        }

        ksort($attributesTableInitialData);

        return $attributesTableInitialData;
    }
}
