<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Currency\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;

class CurrencyDependencyHelper extends Module
{
    use ContainerHelperTrait;

    /**
     * @var string
     */
    protected const SERVICE_CURRENCY = 'currency';

    /**
     * @var string
     */
    protected const DEFAULT_CURRENCY = 'EUR';

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);

        $this->getContainerHelper()
            ->getContainer()
            ->set(static::SERVICE_CURRENCY, static::DEFAULT_CURRENCY);
    }
}
