<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSynchronization;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilSynchronization\UtilSynchronizationServiceFactory getFactory()
 */
class UtilSynchronizationService extends AbstractService implements UtilSynchronizationServiceInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $array
     *
     * @return array
     */
    public function arrayFilterRecursive(array $array)
    {
        return $this->getFactory()->createArrayFilter()->arrayFilterRecursive($array);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return string
     */
    public function escapeKey($key)
    {
        return $this->getFactory()->createKeyFilter()->escapeKey($key);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Service\UtilSynchronization\Model\EventEntityInterface[] $eventTransfers
     *
     * @return array
     */
    public function getEventTransferIds(array $eventTransfers)
    {
        return $this->getFactory()->createEventEntity()->getEventTransferIds($eventTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Service\UtilSynchronization\Model\EventEntityInterface[] $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return array
     */
    public function getEventTransferForeignKeys(array $eventTransfers, $foreignKeyColumnName)
    {
        return $this->getFactory()->createEventEntity()->getEventTransferForeignKeys($eventTransfers, $foreignKeyColumnName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $eventTransfers
     * @param array $columns
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    public function getEventTransfersByModifiedColumns(array $eventTransfers, array $columns)
    {
        return $this->getFactory()->createEventEntity()->getEventTransfersByModifiedColumns($eventTransfers, $columns);
    }

}
