<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\Console\Helper;

use Spryker\Glue\GlueApplication\Descriptor\TextDescriptor;
use Symfony\Component\Console\Helper\DescriptorHelper as BaseDescriptorHelper;

class DescriptorHelper extends BaseDescriptorHelper
{
    /**
     * @param \Spryker\Glue\GlueApplication\Descriptor\TextDescriptor $textDescriptor
     */
    public function __construct(TextDescriptor $textDescriptor)
    {
        parent::__construct();

        $this
            ->register('txt', $textDescriptor);
    }
}
