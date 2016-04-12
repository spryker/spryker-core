<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StateMachine\Business\Logger;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog;
use Spryker\Shared\Library\System;
use Spryker\Zed\StateMachine\Business\Process\EventInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface;
use Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface;

class TransitionLog implements TransitionLogInterface
{

    const SAPI_CLI = 'cli';
    const QUERY_STRING = 'QUERY_STRING';
    const DOCUMENT_URI = 'DOCUMENT_URI';
    const ARGV = 'argv';

    /**
     * @var \Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog[]
     */
    protected $logEntities;

    /**
     * @param \Spryker\Zed\StateMachine\Business\Process\EventInterface $event
     *
     * @return void
     */
    public function setEvent(EventInterface $event)
    {
        $nameEvent = $event->getName();

        if ($event->isOnEnter()) {
            $nameEvent .= ' (on enter)';
        }

        foreach ($this->logEntities as $logEntity) {
            $logEntity->setEvent($nameEvent);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer[] $stateMachineItems
     *
     * @return void
     */
    public function init(array $stateMachineItems)
    {
        $this->logEntities = [];
        foreach ($stateMachineItems as $stateMachineItem) {
            $logEntity = $this->initEntity($stateMachineItem);
            $this->logEntities[$stateMachineItem->getIdentifier()] = $logEntity;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    public function addCommand(StateMachineItemTransfer $stateMachineItemTransfer, CommandPluginInterface $command)
    {
        $this->logEntities[$stateMachineItemTransfer->getIdentifier()]->setCommand(get_class($command));
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param \Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface $condition
     *
     * @return void
     */
    public function addCondition(StateMachineItemTransfer $stateMachineItemTransfer, ConditionPluginInterface $condition)
    {
        $this->logEntities[$stateMachineItemTransfer->getIdentifier()]->setCondition(get_class($condition));
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $stateName
     *
     * @return void
     */
    public function addSourceState(StateMachineItemTransfer $stateMachineItemTransfer, $stateName)
    {
        $this->logEntities[$stateMachineItemTransfer->getIdentifier()]->setSourceState($stateName);
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     * @param string $stateName
     *
     * @return void
     */
    public function addTargetState(StateMachineItemTransfer $stateMachineItemTransfer, $stateName)
    {
        $this->logEntities[$stateMachineItemTransfer->getIdentifier()]->setTargetState($stateName);
    }

    /**
     * @param bool $error
     *
     * @return void
     */
    public function setIsError($error)
    {
        foreach ($this->logEntities as $logEntity) {
            $logEntity->setIsError($error);
        }
    }

    /**
     * @param string $errorMessage
     *
     * @return void
     */
    public function setErrorMessage($errorMessage)
    {
        foreach ($this->logEntities as $logEntity) {
            $logEntity->setErrorMessage($errorMessage);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog
     */
    protected function initEntity(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $stateMachineTransitionLogEntity = $this->createStateMachineTransitionLogEntity();
        $stateMachineTransitionLogEntity->setIdentifier($stateMachineItemTransfer->getIdentifier());
        $stateMachineTransitionLogEntity->setFkStateMachineProcess(
            $stateMachineItemTransfer->getIdStateMachineProcess()
        );
        $stateMachineTransitionLogEntity->setHostname(System::getHostname());

        $path = $this->getExecutorPath();
        $stateMachineTransitionLogEntity->setPath($path);

        $params = [];
        if (!empty($_SERVER[self::QUERY_STRING])) {
            $params = $this->getParamsFromQueryString($_SERVER[self::QUERY_STRING]);
        }

        $stateMachineTransitionLogEntity->setParams($params);

        return $stateMachineTransitionLogEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\StateMachineItemTransfer $stateMachineItemTransfer
     *
     * @return void
     */
    public function save(StateMachineItemTransfer $stateMachineItemTransfer)
    {
        $this->logEntities[$stateMachineItemTransfer->getIdentifier()]->save();
    }

    /**
     * @return void
     */
    public function saveAll()
    {
        foreach ($this->logEntities as $logEntity) {
            if ($logEntity->isModified()) {
                $logEntity->save();
            }
        }
        $this->logEntities = [];
    }

    /**
     * @param array $params
     * @param array &$result
     *
     * @return void
     */
    protected function getOutputParams(array $params, array &$result)
    {
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $this->getOutputParams($value, $result);
            } else {
                $result[] = $key . '=' . $value;
            }
        }
    }

    /**
     * @return \Orm\Zed\StateMachine\Persistence\SpyStateMachineTransitionLog
     */
    protected function createStateMachineTransitionLogEntity()
    {
        return new SpyStateMachineTransitionLog();
    }

    /**
     * @param string $queryString
     *
     * @return array
     */
    protected function getParamsFromQueryString($queryString)
    {
        return explode('&', $queryString);
    }

    /**
     * @return string
     */
    protected function getExecutorPath()
    {
        if (PHP_SAPI !== self::SAPI_CLI) {
            return $_SERVER[self::DOCUMENT_URI];
        }

        $path = self::SAPI_CLI;
        if (isset($_SERVER[self::ARGV]) && is_array($_SERVER[self::ARGV])) {
            $path = implode(' ', $_SERVER[self::ARGV]);
        }

        return $path;
    }

}
