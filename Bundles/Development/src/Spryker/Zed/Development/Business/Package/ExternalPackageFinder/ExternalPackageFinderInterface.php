<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Package\ExternalPackageFinder;

use Generated\Shared\Transfer\PackageFilterTransfer;

interface ExternalPackageFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PackageFilterTransfer|null $packageFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PackageTransfer[]
     */
    public function getExternalPackages(?PackageFilterTransfer $packageFilterTransfer = null): array;
}
