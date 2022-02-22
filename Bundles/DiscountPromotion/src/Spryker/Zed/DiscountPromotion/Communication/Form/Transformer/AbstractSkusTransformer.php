<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class AbstractSkusTransformer implements DataTransformerInterface
{
    /**
     * @param array<string>|null $value SKUs.
     *
     * @return string
     */
    public function transform($value): string
    {
        if (!$value) {
            return '';
        }

        return implode(', ', $value);
    }

    /**
     * @param string $value SKUs as string.
     *
     * @return array<string>
     */
    public function reverseTransform($value): array
    {
        $skus = [];
        foreach (explode(',', $value) as $sku) {
            $trimmedSku = trim($sku);
            if ($trimmedSku) {
                $skus[] = $trimmedSku;
            }
        }

        return array_unique($skus);
    }
}
