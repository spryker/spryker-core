<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder;

class SprykerPathBuilder extends AbstractPathBuilder implements PathBuilderInterface
{
    protected const ORGANIZATION = 'Spryker';

    protected const LOOKUP_NAMESPACES = [
        'src' => 'Spryker',
        'tests' => 'SprykerTest',
    ];
}
