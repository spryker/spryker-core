<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Dependency\External;

use Countable;
use IteratorAggregate;

interface RestRequestValidatorToFinderAdapterInterface extends IteratorAggregate, Countable
{
    /**
     * @param array $dirs
     *
     * @return $this
     */
    public function in(array $dirs);

    /**
     * @param string $pattern
     *
     * @return $this
     */
    public function name(string $pattern);

    /**
     * @return $this
     */
    public function reset();
}
