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
     * @param array<string>|null $skusArray
     *
     * @return string
     */
    public function transform($skusArray): string
    {
        if (!$skusArray) {
            return '';
        }

        return implode(', ', $skusArray);
    }

    /**
     * @param string $skusAsString
     *
     * @return array<string>
     */
    public function reverseTransform($skusAsString): array
    {
        $skus = [];
        foreach (explode(',', $skusAsString) as $sku) {
            $trimmedSku = trim($sku);
            if ($trimmedSku) {
                $skus[] = $trimmedSku;
            }
        }

        return array_unique($skus);
    }
}
