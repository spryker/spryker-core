<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Testify\Locator;

use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Shared\Testify\Exception\InvalidNamespacesException;

class BusinessLocator implements LocatorLocatorInterface
{

    /**
     * @var array
     */
    protected $namespaces;

    /**
     * @param array $namespaces
     */
    public function __construct(array $namespaces)
    {
        $this->validateNamespaces($namespaces);
        $this->namespaces = $namespaces;
    }

    /**
     * @param array $namespaces
     *
     * @throws \Spryker\Shared\Testify\Exception\InvalidNamespacesException
     *
     * @return void
     */
    protected function validateNamespaces(array $namespaces)
    {
        if (count($namespaces) === 0) {
            throw new InvalidNamespacesException('You need to pass at least one namespace to the locator');
        }
    }

    /**
     * @param string $bundle
     * @param array|null $arguments
     *
     * @return void
     */
    public function __call($bundle, array $arguments = null)
    {

    }


}
