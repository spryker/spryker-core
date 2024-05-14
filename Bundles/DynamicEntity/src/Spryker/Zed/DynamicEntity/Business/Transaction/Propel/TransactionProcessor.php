<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Transaction\Propel;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Spryker\Zed\DynamicEntity\Dependency\External\DynamicEntityToConnectionInterface;

class TransactionProcessor implements TransactionProcessorInterface
{
    /**
     * @var \Spryker\Zed\DynamicEntity\Dependency\External\DynamicEntityToConnectionInterface
     */
    protected DynamicEntityToConnectionInterface $propelConnection;

    /**
     * @param \Spryker\Zed\DynamicEntity\Dependency\External\DynamicEntityToConnectionInterface $propelConnection
     */
    public function __construct(DynamicEntityToConnectionInterface $propelConnection)
    {
        $this->propelConnection = $propelConnection;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return bool
     */
    public function startPerItemTransaction(DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer): bool
    {
        if ($dynamicEntityCollectionRequestTransfer->getIsTransactional() !== false) {
            return false;
        }

        return $this->propelConnection->beginTransaction();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return bool
     */
    public function endPerItemTransaction(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): bool {
        if ($dynamicEntityCollectionRequestTransfer->getIsTransactional() !== false) {
            return false;
        }

        if (count($dynamicEntityCollectionResponseTransfer->getErrors()) > 0) {
            return $this->propelConnection->rollBack();
        }

        return $this->propelConnection->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return bool
     */
    public function startAtomicTransaction(DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer): bool
    {
        if ($dynamicEntityCollectionRequestTransfer->getIsTransactional() === false) {
            return false;
        }

        return $this->propelConnection->beginTransaction();
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
     *
     * @return bool
     */
    public function endAtomicTransaction(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer,
        DynamicEntityCollectionResponseTransfer $dynamicEntityCollectionResponseTransfer
    ): bool {
        if ($dynamicEntityCollectionRequestTransfer->getIsTransactional() === false) {
            return false;
        }

        if (count($dynamicEntityCollectionResponseTransfer->getErrors()) > 0) {
            return $this->propelConnection->rollBack();
        }

        return $this->propelConnection->commit();
    }
}
