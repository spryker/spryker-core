<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Form\Transformer;

use ArrayObject;
use Symfony\Component\Form\DataTransformerInterface;

class ImageCollectionTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     *
     * @param \Generated\Shared\Transfer\CategoryImageTransfer[] $value The value in the original representation
     *
     * @return array|null The value in the transformed representation
     */
    public function transform($value)
    {
        if (!$value) {
            return null;
        }

        $formImageCollection = [];
        foreach ($value as $categoryImageTransfer) {
            $formImageCollection[] = $categoryImageTransfer;
        }

        return $formImageCollection;
    }

    /**
     * {@inheritDoc}
     *
     * @param array $value The value in the transformed representation
     *
     * @return \ArrayObject|null The value in the original representation
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        $categoryImageTransferCollection = [];
        /** @var \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer */
        foreach ($value as $sortOrder => $categoryImageTransfer) {
            $categoryImageTransferCollection[] = $categoryImageTransfer;
        }

        return new ArrayObject($categoryImageTransferCollection);
    }
}
