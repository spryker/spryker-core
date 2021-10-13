<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class EmptyStringTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function transform($value)
    {
        if ($value === null) {
            return '';
        }

        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function reverseTransform($value)
    {
        return $value;
    }
}
