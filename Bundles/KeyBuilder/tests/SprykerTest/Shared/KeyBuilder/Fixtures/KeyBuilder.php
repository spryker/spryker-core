<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\KeyBuilder\Fixtures;

use Spryker\Shared\KeyBuilder\KeyBuilderTrait;

class KeyBuilder
{
    use KeyBuilderTrait;

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'key-builder';
    }

    /**
     * @param string $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return 'identifier.' . $data;
    }
}
