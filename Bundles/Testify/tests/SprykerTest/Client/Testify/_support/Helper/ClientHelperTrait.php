<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Testify\Helper;

use Codeception\Module;

trait ClientHelperTrait
{
    /**
     * @return \SprykerTest\Client\Testify\Helper\ClientHelper
     */
    protected function getClientHelper(): ClientHelper
    {
        /** @var \SprykerTest\Client\Testify\Helper\ClientHelper $clientHelper */
        $clientHelper = $this->getModule('\\' . ClientHelper::class);

        return $clientHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
