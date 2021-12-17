<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueHttp;

use Spryker\Glue\GlueHttp\GlueContext\GlueContextHttpExpander;
use Spryker\Glue\GlueHttp\GlueContext\GlueContextHttpExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class GlueHttpFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\GlueHttp\GlueContext\GlueContextHttpExpanderInterface
     */
    public function createGlueContextHttpExpander(): GlueContextHttpExpanderInterface
    {
        return new GlueContextHttpExpander();
    }
}
