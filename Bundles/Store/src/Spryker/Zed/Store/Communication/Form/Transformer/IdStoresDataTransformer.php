<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication\Form\Transformer;

use Symfony\Component\Form\DataTransformerInterface;

class IdStoresDataTransformer implements DataTransformerInterface
{
    /**
     * @param array|null $value Store IDs.
     *
     * @return string
     */
    public function transform($value)
    {
        /** @phpstan-var string */
        return json_encode($value);
    }

    /**
     * @param string $value JSON of Store IDs.
     *
     * @return array|null
     */
    public function reverseTransform($value)
    {
        return json_decode($value, true);
    }
}
