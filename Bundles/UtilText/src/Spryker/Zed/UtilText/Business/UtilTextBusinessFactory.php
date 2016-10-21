<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilText\Business;

use Spryker\Shared\UtilText\Slug;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\UtilText\UtilTextConfig getConfig()
 */
class UtilTextBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Shared\UtilText\SlugInterface
     */
    public function createTextSlug()
    {
        return new Slug();
    }

}
