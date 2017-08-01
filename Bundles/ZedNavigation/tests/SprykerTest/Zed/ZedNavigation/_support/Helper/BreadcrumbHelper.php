<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Helper;

use Codeception\Module;

class BreadcrumbHelper extends Module
{

    /**
     * @param string $breadcrumb
     *
     * @return void
     */
    public function seeBreadcrumbNavigation($breadcrumb)
    {
        $breadcrumb = str_replace('/', ' ', $breadcrumb);

        $webDriver = $this->getWebdriver();
        $webDriver->see($breadcrumb, '//ol[@class="breadcrumb"]');
    }

    /**
     * @return \Codeception\Module|\Codeception\Module\WebDriver
     */
    private function getWebdriver()
    {
        return $this->getModule('WebDriver');
    }

}
