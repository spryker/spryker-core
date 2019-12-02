<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HealthCheck\ChainFilter;

interface ChainFilterAddInterface
{
    /**
     * @param \Spryker\Shared\HealthCheck\ChainFilter\ChainFilterInterface $chainFilter
     *
     * @return $this
     */
    public function addFilter(ChainFilterInterface $chainFilter);
}
