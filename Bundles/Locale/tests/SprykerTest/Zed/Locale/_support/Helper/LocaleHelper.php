<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Locale\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Spryker\Zed\Locale\Communication\Plugin\Application\LocaleApplicationPlugin;
use SprykerTest\Zed\Application\Helper\ApplicationHelperTrait;

class LocaleHelper extends Module
{
    use ApplicationHelperTrait;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->getApplicationHelper()->addApplicationPlugin(new LocaleApplicationPlugin());
    }
}
