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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\NodeTransfer> $value
     *
     * @return array<\Generated\Shared\Transfer\NodeTransfer>
     */
    public function transform($value): array
    {
        return $value->getArrayCopy();
    }

    /**
     * @param array<\Generated\Shared\Transfer\NodeTransfer> $value
     *
     * @return \ArrayObject<int|string, \Generated\Shared\Transfer\NodeTransfer>
     */
    public function reverseTransform($value)
    {
        return new ArrayObject($value);
    }
}
