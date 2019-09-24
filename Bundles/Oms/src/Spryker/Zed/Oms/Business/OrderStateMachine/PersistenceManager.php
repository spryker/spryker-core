<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemStateQuery;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcessQuery;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Oms\Business\Exception\ProcessNotActiveException;
use Spryker\Zed\Oms\OmsConfig;

class PersistenceManager implements PersistenceManagerInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Oms\OmsConfig
     */
    protected $omsConfig;

    /**
     * @param \Spryker\Zed\Oms\OmsConfig $omsConfig
     */
    public function __construct(OmsConfig $omsConfig)
    {
        $this->omsConfig = $omsConfig;
    }

    /**
     * @param string $stateName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getStateEntity($stateName)
    {
        return $this->getTransactionHandler()->handleTransaction(static function () use ($stateName): SpyOmsOrderItemState {
            $stateEntity = SpyOmsOrderItemStateQuery::create()->findOneByName($stateName);

            if ($stateEntity === null) {
                $stateEntity = new SpyOmsOrderItemState();
                $stateEntity->setName($stateName);
                $stateEntity->save();
            }

            return $stateEntity;
        });
    }

    /**
     * @param string $processName
     *
     * @throws \Spryker\Zed\Oms\Business\Exception\ProcessNotActiveException
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    public function getProcessEntity($processName)
    {
        if (!$this->isProcessActive($processName)) {
            throw new ProcessNotActiveException(sprintf(
                'Process with name "%s" is not in active process list. You can add it by modifying your "OmsConstants::ACTIVE_PROCESSES" environment variable constant.',
                $processName
            ));
        }

        return $this->getTransactionHandler()->handleTransaction(static function () use ($processName): SpyOmsOrderProcess {
            $processEntity = SpyOmsOrderProcessQuery::create()->findOneByName($processName);

            if ($processEntity === null) {
                $processEntity = new SpyOmsOrderProcess();
                $processEntity->setName($processName);
                $processEntity->save();
            }

            return $processEntity;
        });
    }

    /**
     * @param string $processName
     *
     * @return bool
     */
    protected function isProcessActive($processName)
    {
        return in_array($processName, $this->omsConfig->getActiveProcesses());
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getInitialStateEntity()
    {
        return $this->getStateEntity($this->omsConfig->getInitialStatus());
    }
}
