<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Business\Fixtures;

use SprykerEngine\Zed\Kernel\Business\Factory as BusinessFactory;

/**
 * @group Kernel
 * @group Business
 * @group Factory
 */
class Factory extends BusinessFactory
{

    /**
     * @var string
     */
    protected $classNamePattern = '\\Unit\\{{namespace}}\\Zed\\{{bundle}}{{store}}\\Business\\Fixtures\\';

}
