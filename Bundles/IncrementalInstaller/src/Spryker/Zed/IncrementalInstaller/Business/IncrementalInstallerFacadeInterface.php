<?php

 /**
  * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
  * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
  */

namespace Spryker\Zed\IncrementalInstaller\Business;

use Generated\Shared\Transfer\IncrementalInstallerCollectionRequestTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCollectionTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCriteriaTransfer;

/**
 * @method \Spryker\Zed\IncrementalInstaller\Business\IncrementalInstallerBusinessFactory getFactory()
 */
interface IncrementalInstallerFacadeInterface
{
    /**
     * Specification:
     * - Creates incremental installer entities in the database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\IncrementalInstallerCollectionRequestTransfer $incrementalInstallerCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer
     */
    public function createIncrementalInstallerCollection(
        IncrementalInstallerCollectionRequestTransfer $incrementalInstallerCollectionRequestTransfer
    ): IncrementalInstallerCollectionResponseTransfer;

    /**
     * Specification:
     * - Retrieves incremental installer entities from the database by provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\IncrementalInstallerCriteriaTransfer $incrementalInstallerCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerCollectionTransfer
     */
    public function getIncrementalInstallerCollection(
        IncrementalInstallerCriteriaTransfer $incrementalInstallerCriteriaTransfer
    ): IncrementalInstallerCollectionTransfer;
}
