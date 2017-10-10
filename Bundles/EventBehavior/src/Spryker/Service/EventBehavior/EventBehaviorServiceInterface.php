<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\EventBehavior;

interface EventBehaviorServiceInterface
{

    /**
     * Specification:
     *  - Filters null elements of an array recursively
     *
     * @api
     *
     * @param array $array
     *
     * @return array
     */
    public function arrayFilterRecursive(array $array);

    /**
     * Specification:
     *  - Return Ids in eventTransfers
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return array
     */
    public function getEventTransferIds(array $eventTransfers);

    /**
     * Specification:
     *  - Return ForeignKeys in eventTransfers
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return array
     */
    public function getEventTransferForeignKeys(array $eventTransfers, $foreignKeyColumnName);

    /**
     * Specification:
     *  - Return eventTransfers with matched modifiedColumns
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param array $columns
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    public function getEventTransfersByModifiedColumns(array $eventTransfers, array $columns);

}
