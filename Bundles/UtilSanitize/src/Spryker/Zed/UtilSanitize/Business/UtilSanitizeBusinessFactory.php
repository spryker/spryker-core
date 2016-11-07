<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilSanitize\Business;

use Spryker\Shared\UtilSanitize\Html;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\UtilSanitize\UtilSanitizeConfig getConfig()
 */
class UtilSanitizeBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Shared\UtilSanitize\HtmInterface
     */
    public function createHtml()
    {
        return new Html();
    }

}
