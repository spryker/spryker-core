<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency;

interface BundleParserInterface
{
    /**
     * @param string $bundleName
     *
     * @return \Generated\Shared\Transfer\BundleDependencyCollectionTransfer
     */
    public function parseOutgoingDependencies($bundleName);
}
