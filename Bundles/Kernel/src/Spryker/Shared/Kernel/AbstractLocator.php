<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

use Exception;
use Spryker\Shared\Kernel\Locator\LocatorInterface;

abstract class AbstractLocator implements LocatorInterface
{
    /**
     * @var string
     */
    protected $bundle;

    /**
     * @var string
     */
    protected $layer;

    /**
     * @var string
     */
    protected $suffix;

    /**
     * @var string
     */
    protected $application;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {
        if ($this->application === null) {
            throw new Exception('Properties missing for: ' . static::class);
        }
    }

    /**
     * @param string $bundle
     *
     * @return object
     */
    abstract public function locate($bundle);
}
