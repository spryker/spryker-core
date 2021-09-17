<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Package\PackageFinder;

/**
 * @deprecated Use `spryker/module-finder` instead.
 */
interface PackageFinderInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\PackageTransfer>
     */
    public function getPackages(): array;
}
