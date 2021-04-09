<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\Transformer;

use ArrayObject;
use Symfony\Component\Form\DataTransformerInterface;

class CategoryExtraParentsTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\NodeTransfer[] $value
     *
     * @return \Generated\Shared\Transfer\NodeTransfer[]
     */
    public function transform($value): array
    {
        return (array)$value;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[] $value
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function reverseTransform($value)
    {
        return new ArrayObject($value);
    }
}
