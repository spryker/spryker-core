<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\Console\Helper;

use Spryker\Yves\Router\Plugin\Console\Descriptor\TextDescriptor;
use Symfony\Component\Console\Helper\DescriptorHelper as BaseDescriptorHelper;

class DescriptorHelper extends BaseDescriptorHelper
{
    public function __construct()
    {
        parent::__construct();

        $this
            ->register('txt', new TextDescriptor());
    }
}
