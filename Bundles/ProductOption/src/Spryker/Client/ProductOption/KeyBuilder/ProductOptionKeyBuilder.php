<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption\KeyBuilder;

use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderTrait;

class ProductOptionKeyBuilder implements KeyBuilderInterface
{
    use KeyBuilderTrait;

    /**
     * @param int $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return 'product_option.' . $data;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'resource';
    }
}
