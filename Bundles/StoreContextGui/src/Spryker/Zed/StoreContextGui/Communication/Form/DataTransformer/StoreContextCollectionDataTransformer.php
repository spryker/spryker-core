<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Form\DataTransformer;

use ArrayObject;
use Symfony\Component\Form\DataTransformerInterface;

class StoreContextCollectionDataTransformer implements DataTransformerInterface
{
    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\StoreContextCollectionTransfer>|null $value
     *
     * @return array<\Generated\Shared\Transfer\StoreContextCollectionTransfer>|null
     */
    public function transform(mixed $value): ?array
    {
        if (!$value) {
            return null;
        }

        return $value->getArrayCopy();
    }

    /**
     * @param array<\Generated\Shared\Transfer\StoreContextCollectionTransfer|null> $value
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\StoreContextCollectionTransfer>
     */
    public function reverseTransform(mixed $value): ArrayObject
    {
        $result = new ArrayObject();

        if (!$value) {
            return $result;
        }

        foreach ($value as $storeContextData) {
            if (!$storeContextData) {
                continue;
            }

            $result->append($storeContextData);
        }

        return $result;
    }
}
