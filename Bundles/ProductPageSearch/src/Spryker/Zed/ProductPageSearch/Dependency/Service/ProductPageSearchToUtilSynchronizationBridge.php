<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Service;

class ProductPageSearchToUtilSynchronizationBridge implements ProductPageSearchToUtilSynchronizationInterface
{

    /**
     * @var \Spryker\Service\UtilSynchronization\UtilSynchronizationServiceInterface
     */
    protected $utilSynchronization;

    /**
     * @param \Spryker\Service\UtilSynchronization\UtilSynchronizationServiceInterface $utilSynchronization
     */
    public function __construct($utilSynchronization)
    {
        $this->utilSynchronization = $utilSynchronization;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function arrayFilterRecursive(array $array)
    {
        return $this->utilSynchronization->arrayFilterRecursive($array);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return array
     */
    public function getEventTransferIds(array $eventTransfers)
    {
        return $this->utilSynchronization->getEventTransferIds($eventTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return array
     */
    public function getEventTransferForeignKeys(array $eventTransfers, $foreignKeyColumnName)
    {
        return $this->utilSynchronization->getEventTransferForeignKeys($eventTransfers, $foreignKeyColumnName);
    }

    /**
     * @param array $eventTransfers
     * @param array $columns
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    public function getEventTransfersByModifiedColumns(array $eventTransfers, array $columns)
    {
        return $this->utilSynchronization->getEventTransfersByModifiedColumns($eventTransfers, $columns);
    }

}
