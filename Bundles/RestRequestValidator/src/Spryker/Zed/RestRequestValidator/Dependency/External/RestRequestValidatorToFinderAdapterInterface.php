<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Dependency\External;

use Countable;
use IteratorAggregate;
use Symfony\Component\Finder\Finder;

interface RestRequestValidatorToFinderAdapterInterface extends IteratorAggregate, Countable
{
    /**
     * @param array $dirs
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function in(array $dirs): Finder;

    /**
     * @param string $pattern
     *
     * @return \Symfony\Component\Finder\Finder
     */
    public function name(string $pattern): Finder;
}
