<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSynchronization\Model;

class EventEntity implements EventEntityInterface
{

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return array
     */
    public function getEventTransferIds(array $eventTransfers)
    {
        $ids = [];
        foreach ($eventTransfers as $eventTransfer) {
            $ids[] = $eventTransfer->getId();
        }

        return $ids;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return array
     */
    public function getEventTransferForeignKeys(array $eventTransfers, $foreignKeyColumnName)
    {
        if ($foreignKeyColumnName === null) {
            return [];
        }

        $foreignKeys = [];
        foreach ($eventTransfers as $eventTransfer) {
            $value = $eventTransfer->getForeignKeys()[$foreignKeyColumnName];
            if ($value !== null) {
                $foreignKeys[] = $value;
            }
        }

        return $foreignKeys;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param array $columns
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    public function getEventTransfersByModifiedColumns(array $eventTransfers, array $columns)
    {
        $validEventTransfers = [];
        foreach ($eventTransfers as $eventTransfer) {
            if ($this->checkColumnsExists($columns, $eventTransfer->getModifiedColumns())) {
                $validEventTransfers[] = $eventTransfer;
            }
        }

        return $validEventTransfers;
    }

    /**
     * @param array $columns
     * @param array $modifiedColumns
     *
     * @return bool
     */
    protected function checkColumnsExists(array $columns, array $modifiedColumns)
    {
        foreach ($columns as $column) {
            if (in_array($column, $modifiedColumns)) {
                return true;
            }
        }

        return false;
    }

}
