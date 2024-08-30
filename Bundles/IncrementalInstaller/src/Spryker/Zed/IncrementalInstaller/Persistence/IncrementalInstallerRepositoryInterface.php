<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\IncrementalInstaller\Persistence;

use Generated\Shared\Transfer\IncrementalInstallerCollectionTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCriteriaTransfer;

interface IncrementalInstallerRepositoryInterface
{
    /**
     * @return array<string>
     */
    public function getExecutedInstallers(): array;

    /**
     * @return int
     */
    public function getLastBatch(): int;

    /**
     * @param \Generated\Shared\Transfer\IncrementalInstallerCriteriaTransfer $incrementalInstallerCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerCollectionTransfer
     */
    public function getIncrementalInstallerCollection(
        IncrementalInstallerCriteriaTransfer $incrementalInstallerCriteriaTransfer
    ): IncrementalInstallerCollectionTransfer;
}
