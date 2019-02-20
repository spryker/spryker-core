<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\Finder\PathBuilder;

class SprykerMerchantPortalPathBuilder extends AbstractPathBuilder implements PathBuilderInterface
{
    protected const ORGANIZATION = 'SprykerMerchantPortal';

    protected const LOOKUP_NAMESPACES = [
        'src' => 'SprykerMerchantPortal',
        'tests' => 'SprykerMerchantPortalTest',
    ];
}
