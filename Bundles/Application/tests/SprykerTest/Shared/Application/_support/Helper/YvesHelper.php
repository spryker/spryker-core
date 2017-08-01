<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Application\Helper;

use Codeception\Module;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;

class YvesHelper extends Module
{

    /**
     * @return $this
     */
    public function amYves()
    {
        $url = Config::get(ApplicationConstants::BASE_URL_YVES);

        $this->getModule('WebDriver')->_reconfigure(['url' => $url]);

        return $this;
    }

}
