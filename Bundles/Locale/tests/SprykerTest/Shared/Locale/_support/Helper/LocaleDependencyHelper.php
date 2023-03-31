<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Locale\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use SprykerTest\Service\Container\Helper\ContainerHelperTrait;

class LocaleDependencyHelper extends Module
{
    use ContainerHelperTrait;

    /**
     * @var string
     */
    protected const SERVICE_LOCALE = 'locale';

    /**
     * @var string
     */
    protected const LOCALE_DEFAULT = 'en_US';

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
            ->set(static::SERVICE_LOCALE, static::LOCALE_DEFAULT);
    }
}
