<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\IncrementalInstaller\Persistence;

use Generated\Shared\Transfer\IncrementalInstallerTransfer;

interface IncrementalInstallerEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\IncrementalInstallerTransfer $incrementalInstallerTransfer
     *
     * @return void
     */
    public function createIncrementalInstaller(IncrementalInstallerTransfer $incrementalInstallerTransfer): void;
}
