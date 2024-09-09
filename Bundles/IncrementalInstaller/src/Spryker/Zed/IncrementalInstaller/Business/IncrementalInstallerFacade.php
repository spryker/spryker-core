<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\IncrementalInstaller\Business;

use Generated\Shared\Transfer\IncrementalInstallerCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCollectionRequestTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCollectionTransfer;
use Generated\Shared\Transfer\IncrementalInstallerCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\IncrementalInstaller\Business\IncrementalInstallerBusinessFactory getFactory()
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\IncrementalInstaller\Persistence\IncrementalInstallerRepositoryInterface getRepository()
 */
class IncrementalInstallerFacade extends AbstractFacade implements IncrementalInstallerFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\IncrementalInstallerCollectionRequestTransfer $incrementalInstallerCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer
     */
    public function createIncrementalInstallerCollection(
        IncrementalInstallerCollectionRequestTransfer $incrementalInstallerCollectionRequestTransfer
    ): IncrementalInstallerCollectionResponseTransfer {
        return $this->getFactory()
            ->createIncrementalInstallerCreator()
            ->createIncrementalInstallerCollection($incrementalInstallerCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\IncrementalInstallerCriteriaTransfer $incrementalInstallerCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerCollectionTransfer
     */
    public function getIncrementalInstallerCollection(
        IncrementalInstallerCriteriaTransfer $incrementalInstallerCriteriaTransfer
    ): IncrementalInstallerCollectionTransfer {
        return $this->getRepository()->getIncrementalInstallerCollection($incrementalInstallerCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\IncrementalInstallerCollectionDeleteCriteriaTransfer $incrementalInstallerCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\IncrementalInstallerCollectionResponseTransfer
     */
    public function deleteIncrementalInstallerCollection(
        IncrementalInstallerCollectionDeleteCriteriaTransfer $incrementalInstallerCollectionDeleteCriteriaTransfer
    ): IncrementalInstallerCollectionResponseTransfer {
        return $this->getFactory()
            ->createIncrementalInstallerDeleter()
            ->deleteIncrementalInstallerCollection($incrementalInstallerCollectionDeleteCriteriaTransfer);
    }
}
