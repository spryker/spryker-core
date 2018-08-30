<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\Finder\Factory;

use Generated\Shared\Transfer\ClassInformationTransfer;

interface FactoryInfoFinderInterface
{
    /**
     * @param string $className
     *
     * @return \Generated\Shared\Transfer\ClassInformationTransfer
     */
    public function findFactoryInformation(string $className): ClassInformationTransfer;
}
