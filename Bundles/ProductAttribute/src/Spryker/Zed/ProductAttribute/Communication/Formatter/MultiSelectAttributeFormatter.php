<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Communication\Formatter;

use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Spryker\Shared\ProductAttribute\ProductAttributeConfig;

class MultiSelectAttributeFormatter implements MultiSelectAttributeFormatterInterface
{
    /**
     * @uses \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainer::INPUT_TYPE
     *
     * @var string
     */
    protected const INPUT_TYPE = 'input_type';

    /**
     * @uses \Spryker\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainer::LOCALE_CODE
     *
     * @var string
     */
    protected const LOCALE_CODE = 'locale_code';

    /**
     * @param array<mixed> $attributes
     * @param array<mixed> $formattedAttributes
     *
     * @return array<mixed>
     */
    public function format(array $attributes, array $formattedAttributes): array
    {
        $multiSelectAttributeKeys = $this->extractMultiSelectAttributeKeys($attributes);

        return $this->formatMultiSelectAttributesToArray($multiSelectAttributeKeys, $formattedAttributes);
    }

    /**
     * @param array<mixed> $attributes
     *
     * @return array<mixed>
     */
    protected function extractMultiSelectAttributeKeys(array $attributes): array
    {
        $multiSelectAttributeKeys = [];

        foreach ($attributes as $attribute) {
            $inputType = $attribute[static::INPUT_TYPE] ?? null;

            if ($inputType === ProductAttributeConfig::INPUT_TYPE_MULTISELECT) {
                $multiSelectAttributeKeys[$attribute[static::LOCALE_CODE]][] = $attribute[ProductAttributeKeyTransfer::KEY];
            }
        }

        return $multiSelectAttributeKeys;
    }

    /**
     * @param array<array<string>> $multiSelectAttributeKeys
     * @param array<mixed> $formattedAttributes
     *
     * @return array<mixed>
     */
    protected function formatMultiSelectAttributesToArray(array $multiSelectAttributeKeys, array $formattedAttributes): array
    {
        foreach ($multiSelectAttributeKeys as $localeCode => $multiSelectKeys) {
            if (isset($formattedAttributes[$localeCode])) {
                foreach ($multiSelectKeys as $multiSelectKey) {
                    $value = $formattedAttributes[$localeCode][$multiSelectKey] ?? null;

                    if (!$value) {
                        continue;
                    }

                    $formattedAttributes[$localeCode][$multiSelectKey] = array_map('trim', explode(',', $value));
                }
            }
        }

        return $formattedAttributes;
    }
}
