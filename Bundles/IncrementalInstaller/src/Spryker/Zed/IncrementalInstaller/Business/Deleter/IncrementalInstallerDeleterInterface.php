<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\IncrementalInstaller\Business\Deleter;

use Generated\Shared\Transfer\IncrementalInstallerCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer;

interface IncrementalInstallerDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\IncrementalInstallerCollectionDeleteCriteriaTransfer $incrementalInstallerCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer
     */
    public function deleteIncrementalInstallerCollection(
        IncrementalInstallerCollectionDeleteCriteriaTransfer $incrementalInstallerCollectionDeleteCriteriaTransfer
    ): IncrementalInstallerCollectionResponseTransfer;
}
