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
     * @param array|null $idStoresArray
     *
     * @return string
     */
    public function transform($idStoresArray)
    {
        return json_encode($idStoresArray);
    }

    /**
     * @param string $idStoresJson
     *
     * @return mixed
     */
    public function reverseTransform($idStoresJson)
    {
        return json_decode($idStoresJson, true);
    }
}
