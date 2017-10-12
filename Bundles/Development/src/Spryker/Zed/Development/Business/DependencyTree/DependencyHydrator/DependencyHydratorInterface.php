<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyHydrator;

interface DependencyHydratorInterface
{
    /**
     * @param array $dependency
     *
     * @return void
     */
    public function hydrate(array &$dependency);
}
