<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NewRelic\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @method \Spryker\Zed\NewRelic\Communication\NewRelicCommunicationFactory getFactory()
 * @method \Spryker\Zed\NewRelic\Business\NewRelicFacadeInterface getFacade()
 */
class NewRelicConsolePlugin extends AbstractPlugin implements EventSubscriberInterface
{
    const TRANSACTION_NAME_PREFIX = 'vendor/bin/console ';

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    public function onConsoleTerminate(ConsoleTerminateEvent $event)
    {
        $transactionName = $this->getTransactionName($event);
        $hostName = $this->getFactory()->getUtilNetworkService()->getHostName();

        $this->getFactory()->getNewRelicApi()
            ->markAsBackgroundJob()
            ->setNameOfTransaction($transactionName)
            ->addCustomParameter('host', $hostName);

        $this->addArgumentsAsCustomParameter($event);
        $this->addOptionsAsCustomParameter($event);
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return string
     */
    protected function getTransactionName(ConsoleTerminateEvent $event)
    {
        return static::TRANSACTION_NAME_PREFIX . $event->getCommand()->getName();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::TERMINATE => ['onConsoleTerminate'],
        ];
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    protected function addArgumentsAsCustomParameter(ConsoleTerminateEvent $event)
    {
        $this->addCustomParameter($event->getInput()->getArguments());
    }

    /**
     * @param \Symfony\Component\Console\Event\ConsoleTerminateEvent $event
     *
     * @return void
     */
    protected function addOptionsAsCustomParameter(ConsoleTerminateEvent $event)
    {
        $this->addCustomParameter($event->getInput()->getOptions());
    }

    /**
     * @param array $customParameter
     *
     * @return void
     */
    protected function addCustomParameter(array $customParameter)
    {
        $newRelicApi = $this->getFactory()->getNewRelicApi();

        foreach ($customParameter as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
            $newRelicApi->addCustomParameter($key, $value);
        }
    }
}
