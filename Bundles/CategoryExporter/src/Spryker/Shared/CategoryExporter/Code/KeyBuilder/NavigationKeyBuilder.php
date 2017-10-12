<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\CategoryExporter\Code\KeyBuilder;

use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderTrait;

abstract class NavigationKeyBuilder implements KeyBuilderInterface
{
    use KeyBuilderTrait;

    /**
     * @param mixed $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return 'navigation';
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'category';
    }
}
