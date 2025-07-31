<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Business;

use Generated\Shared\Transfer\MerchantFileCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantFileResultTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantFile\Business\MerchantFileBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantFile\Persistence\MerchantFileRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantFile\Persistence\MerchantFileEntityManagerInterface getEntityManager()
 */
class MerchantFileFacade extends AbstractFacade implements MerchantFileFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileResultTransfer
     */
    public function writeMerchantFile(MerchantFileTransfer $merchantFileTransfer): MerchantFileResultTransfer
    {
        return $this
            ->getFactory()
            ->createFileWriteHandler()
            ->writeMerchantFile($merchantFileTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer|null
     */
    public function findMerchantFile(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer): ?MerchantFileTransfer
    {
        return $this->getRepository()->findMerchantFile($merchantFileCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileCollectionTransfer
     */
    public function getMerchantFileCollection(
        MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
    ): MerchantFileCollectionTransfer {
        return $this
            ->getRepository()
            ->getMerchantFileCollection($merchantFileCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return resource
     */
    public function readMerchantFileStream(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer)
    {
        return $this->getFactory()
            ->createMerchantFileReader()
            ->readMerchantFileStream($merchantFileCriteriaTransfer);
    }
}
