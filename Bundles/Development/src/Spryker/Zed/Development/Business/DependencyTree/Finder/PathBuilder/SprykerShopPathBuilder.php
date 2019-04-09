<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder;

class SprykerShopPathBuilder extends AbstractPathBuilder implements PathBuilderInterface
{
    protected const ORGANIZATION = 'SprykerShop';

    protected const LOOKUP_NAMESPACES = [
        'src' => 'SprykerShop',
        'tests' => 'SprykerShopTest',
    ];
}
