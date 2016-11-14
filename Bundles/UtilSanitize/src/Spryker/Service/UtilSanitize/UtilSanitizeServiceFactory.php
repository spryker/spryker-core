<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitize;

use Spryker\Service\UtilSanitize\Model\Html;

class UtilSanitizeServiceFactory
{

    /**
     * @return \Spryker\Service\UtilSanitize\Model\HtmInterface
     */
    public function createHtml()
    {
        return new Html();
    }
}
