<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Oms\Business\Checker\FlagChecker;
use Spryker\Zed\Oms\Business\Checker\FlagCheckerInterface;
use Spryker\Zed\Oms\Business\Checker\OrderChecker;
use Spryker\Zed\Oms\Business\Checker\OrderCheckerInterface;
use Spryker\Zed\Oms\Business\Deleter\OmsEventTimeoutDeleter;
use Spryker\Zed\Oms\Business\Deleter\OmsEventTimeoutDeleterInterface;
use Spryker\Zed\Oms\Business\Deleter\OmsOrderItemStateHistoryDeleter;
use Spryker\Zed\Oms\Business\Deleter\OmsOrderItemStateHistoryDeleterInterface;
use Spryker\Zed\Oms\Business\Deleter\OmsTransitionLogDeleter;
use Spryker\Zed\Oms\Business\Deleter\OmsTransitionLogDeleterInterface;
use Spryker\Zed\Oms\Business\Expander\OrderAggregatedItemStateExpander;
use Spryker\Zed\Oms\Business\Expander\OrderAggregatedItemStateExpanderInterface;
use Spryker\Zed\Oms\Business\Expander\OrderExpander;
use Spryker\Zed\Oms\Business\Expander\OrderExpanderInterface;
use Spryker\Zed\Oms\Business\Expander\OrderItemStateExpander;
use Spryker\Zed\Oms\Business\Expander\OrderItemStateExpanderInterface;
use Spryker\Zed\Oms\Business\Expander\StateHistoryExpander;
use Spryker\Zed\Oms\Business\Expander\StateHistoryExpanderInterface;
use Spryker\Zed\Oms\Business\Lock\TriggerLocker;
use Spryker\Zed\Oms\Business\Mail\MailHandler;
use Spryker\Zed\Oms\Business\Notifier\EventTriggeredNotifier;
use Spryker\Zed\Oms\Business\Notifier\EventTriggeredNotifierInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\Builder;
use Spryker\Zed\Oms\Business\OrderStateMachine\Finder;
use Spryker\Zed\Oms\Business\OrderStateMachine\LockedOrderStateMachine;
use Spryker\Zed\Oms\Business\OrderStateMachine\ManualEventReader;
use Spryker\Zed\Oms\Business\OrderStateMachine\ManualEventReaderInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderItemManualEventReader;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderItemManualEventReaderInterface;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachine;
use Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineFlagReader;
use Spryker\Zed\Oms\Business\OrderStateMachine\PersistenceManager;
use Spryker\Zed\Oms\Business\OrderStateMachine\Timeout;
use Spryker\Zed\Oms\Business\OrderStatusChanged\OrderStatusChangedMessageSender;
use Spryker\Zed\Oms\Business\Process\Event;
use Spryker\Zed\Oms\Business\Process\Process;
use Spryker\Zed\Oms\Business\Process\State;
use Spryker\Zed\Oms\Business\Process\Transition;
use Spryker\Zed\Oms\Business\Reader\ProcessCacheReader;
use Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface;
use Spryker\Zed\Oms\Business\Reader\ReservationReader;
use Spryker\Zed\Oms\Business\Reader\ReservationReaderInterface;
use Spryker\Zed\Oms\Business\Reader\StateMachineReader;
use Spryker\Zed\Oms\Business\Reader\StateMachineReaderInterface;
use Spryker\Zed\Oms\Business\Reservation\ExportReservation;
use Spryker\Zed\Oms\Business\Reservation\ReservationVersionHandler;
use Spryker\Zed\Oms\Business\Reservation\ReservationWriter;
use Spryker\Zed\Oms\Business\Util\ActiveProcessFetcher;
use Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface;
use Spryker\Zed\Oms\Business\Util\Drawer;
use Spryker\Zed\Oms\Business\Util\OrderItemMatrix;
use Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use Spryker\Zed\Oms\Business\Util\Reservation;
use Spryker\Zed\Oms\Business\Util\TimeoutProcessorCollection;
use Spryker\Zed\Oms\Business\Util\TimeoutProcessorCollectionInterface;
use Spryker\Zed\Oms\Business\Util\TransitionLog;
use Spryker\Zed\Oms\Business\Writer\ProcessCacheWriter;
use Spryker\Zed\Oms\Business\Writer\ProcessCacheWriterInterface;
use Spryker\Zed\Oms\OmsDependencyProvider;
use Spryker\Zed\OmsExtension\Dependency\Plugin\OmsLockPluginInterface;

/**
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface getRepository()
 * @method \Spryker\Zed\Oms\Persistence\OmsEntityManagerInterface getEntityManager()
 */
class OmsBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @param array $array
     *
     * @return \Spryker\Zed\Oms\Business\Util\ReadOnlyArrayObject
     */
    public function createUtilReadOnlyArrayObject(array $array = [])
    {
        return new ReadOnlyArrayObject($array);
    }

    /**
     * @param array $logContext
     *
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineInterface
     */
    public function createOrderStateMachine(array $logContext = [])
    {
        return new OrderStateMachine(
            $this->getQueryContainer(),
            $this->createOrderStateMachineBuilder(),
            $this->createUtilTransitionLog($logContext),
            $this->createOrderStateMachineTimeout(),
            $this->createUtilReadOnlyArrayObject($this->getConfig()->getActiveProcesses()),
            $this->getProvidedDependency(OmsDependencyProvider::CONDITION_PLUGINS),
            $this->getProvidedDependency(OmsDependencyProvider::COMMAND_PLUGINS),
            $this->createUtilReservation(),
            $this->getConfig(),
            $this->createEventTriggeredNotifier(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Notifier\EventTriggeredNotifierInterface
     */
    public function createEventTriggeredNotifier(): EventTriggeredNotifierInterface
    {
        return new EventTriggeredNotifier($this->getOmsEventTriggeredListenerPlugins());
    }

    /**
     * @param array $logContext
     *
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\LockedOrderStateMachine
     */
    public function createLockedOrderStateMachine(array $logContext = [])
    {
        return new LockedOrderStateMachine(
            $this->createOrderStateMachine($logContext),
            $this->createTriggerLocker(),
            $this->createOrderStateMachineBuilder(),
            $this->createUtilReadOnlyArrayObject($this->getConfig()->getActiveProcesses()),
            $this->getQueryContainer(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\BuilderInterface
     */
    public function createOrderStateMachineBuilder()
    {
        return new Builder(
            $this->createProcessEvent(),
            $this->createProcessState(),
            $this->createProcessTransition(),
            $this->createProcessProcess(),
            $this->getConfig()->getProcessDefinitionLocation(),
            $this->createProcessCacheReader(),
            $this->createProcessCacheWriter(),
            $this->getConfig(),
            $this->getConfig()->getSubProcessPrefixDelimiter(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\FinderInterface
     */
    public function createOrderStateMachineFinder()
    {
        $config = $this->getConfig();

        return new Finder(
            $this->getQueryContainer(),
            $this->createOrderStateMachineBuilder(),
            $config->getActiveProcesses(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\ManualEventReaderInterface
     */
    public function createManualOrderReader(): ManualEventReaderInterface
    {
        return new ManualEventReader(
            $this->createOrderItemManualEventReader(),
            $this->getSalesFacade(),
            $this->getOmsManualEventGrouperPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\TimeoutInterface
     */
    public function createOrderStateMachineTimeout()
    {
        return new Timeout(
            $this->getQueryContainer(),
            $this->createTimeoutProcessorCollection(),
            $this->getConfig(),
        );
    }

    /**
     * @param array $logContext
     *
     * @return \Spryker\Zed\Oms\Business\Util\TransitionLogInterface
     */
    public function createUtilTransitionLog(array $logContext)
    {
        return new TransitionLog(
            $this->getQueryContainer(),
            $logContext,
            $this->getUtilNetworkService(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\PersistenceManagerInterface
     */
    public function createOrderStateMachinePersistenceManager()
    {
        return new PersistenceManager($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\EventInterface
     */
    public function createProcessEvent()
    {
        return new Event();
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\StateInterface
     */
    public function createProcessState()
    {
        return new State();
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\TransitionInterface
     */
    public function createProcessTransition()
    {
        return new Transition();
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Process\ProcessInterface
     */
    public function createProcessProcess()
    {
        return new Process($this->createUtilDrawer());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Util\DrawerInterface
     */
    public function createUtilDrawer()
    {
        return new Drawer(
            $this->getProvidedDependency(OmsDependencyProvider::COMMAND_PLUGINS),
            $this->getProvidedDependency(OmsDependencyProvider::CONDITION_PLUGINS),
            $this->getGraph()->init('Statemachine', $this->getConfig()->getGraphDefaults(), true, false),
            $this->getUtilTextService(),
            $this->createTimeoutProcessorCollection(),
        );
    }

    /**
     * @return \Spryker\Zed\Graph\Communication\Plugin\GraphPlugin
     */
    public function getGraph()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGIN_GRAPH);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Util\OrderItemMatrix
     */
    public function createUtilOrderItemMatrix()
    {
        return new OrderItemMatrix(
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->getUtilSanitizeService(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Lock\TriggerLocker
     */
    public function createTriggerLocker()
    {
        return new TriggerLocker(
            $this->getQueryContainer(),
            $this->getConfig(),
            $this->getLockPlugin(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Util\ReservationInterface
     */
    public function createUtilReservation()
    {
        return new Reservation(
            $this->createReservationReader(),
            $this->getReservationHandlerPlugins(),
            $this->getStoreFacade(),
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getOmsReservationWriterStrategyPlugins(),
            $this->getReservationHandlerTerminationAwareStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Reader\ReservationReaderInterface
     */
    public function createReservationReader(): ReservationReaderInterface
    {
        return new ReservationReader(
            $this->getRepository(),
            $this->getStoreFacade(),
            $this->createActiveProcessFetcher(),
            $this->getOmsReservationReaderStrategyPlugins(),
            $this->getReservationAggregationStrategyPlugins(),
            $this->getOmsReservationAggregationPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Reservation\ReservationVersionHandlerInterface
     */
    public function createReservationVersionHandler()
    {
        return new ReservationVersionHandler($this->getQueryContainer(), $this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Reservation\ReservationWriterInterface
     */
    public function createReservationWriter()
    {
        return new ReservationWriter($this->getStoreFacade(), $this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Reservation\ExportReservationInterface
     */
    public function createExportReservation()
    {
        return new ExportReservation($this->getStoreFacade(), $this->getQueryContainer(), $this->getReservationExportPlugins());
    }

    /**
     * @return array<\Spryker\Zed\Oms\Dependency\Plugin\ReservationHandlerPluginInterface>
     */
    public function getReservationHandlerPlugins()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGINS_RESERVATION);
    }

    /**
     * @return \Spryker\Zed\Oms\Dependency\Facade\OmsToSalesInterface
     */
    public function getSalesFacade()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\Oms\Dependency\Service\OmsToUtilTextInterface
     */
    public function getUtilTextService()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::FACADE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\Oms\Dependency\Service\OmsToUtilNetworkInterface
     */
    public function getUtilNetworkService()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::SERVICE_UTIL_NETWORK);
    }

    /**
     * @return \Spryker\Zed\Oms\Dependency\Service\OmsToUtilSanitizeInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Mail\MailHandler
     */
    public function createMailHandler()
    {
        $mailHandler = new MailHandler(
            $this->getSalesFacade(),
            $this->getMailFacade(),
            $this->getOmsOrderMailExpanderPlugins(),
        );

        return $mailHandler;
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Util\ActiveProcessFetcherInterface
     */
    public function createActiveProcessFetcher(): ActiveProcessFetcherInterface
    {
        return new ActiveProcessFetcher(
            $this->createUtilReadOnlyArrayObject($this->getConfig()->getActiveProcesses()),
            $this->createOrderStateMachineBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Expander\StateHistoryExpanderInterface
     */
    public function createStateHistoryExpander(): StateHistoryExpanderInterface
    {
        return new StateHistoryExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Expander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander(
            $this->createFlagChecker(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Checker\FlagCheckerInterface
     */
    public function createFlagChecker(): FlagCheckerInterface
    {
        return new FlagChecker($this->createOrderStateMachineBuilder());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Expander\OrderAggregatedItemStateExpanderInterface
     */
    public function createOrderAggregatedItemStateExpander(): OrderAggregatedItemStateExpanderInterface
    {
        return new OrderAggregatedItemStateExpander(
            $this->createOrderItemStateExpander(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Reader\StateMachineReaderInterface
     */
    public function createStateMachineReader(): StateMachineReaderInterface
    {
        return new StateMachineReader(
            $this->getRepository(),
            $this->createOrderStateMachineBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Expander\OrderItemStateExpanderInterface
     */
    public function createOrderItemStateExpander(): OrderItemStateExpanderInterface
    {
        return new OrderItemStateExpander(
            $this->createOrderStateMachineFinder(),
            $this->getRepository(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Checker\OrderCheckerInterface
     */
    public function createOrderChecker(): OrderCheckerInterface
    {
        return new OrderChecker(
            $this->createFlagChecker(),
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Dependency\Facade\OmsToMailInterface
     */
    public function getMailFacade()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\Oms\Dependency\Facade\OmsToStoreFacadeInterface
     */
    public function getStoreFacade()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::FACADE_STORE);
    }

    /**
     * @return array<\Spryker\Zed\Oms\Dependency\Plugin\ReservationExportPluginInterface>
     */
    public function getReservationExportPlugins()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGINS_RESERVATION_EXPORT);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderStateMachineFlagReaderInterface
     */
    public function createOrderStateMachineFlagReader()
    {
        return new OrderStateMachineFlagReader(
            $this->createOrderStateMachineBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStateMachine\OrderItemManualEventReaderInterface
     */
    public function createOrderItemManualEventReader(): OrderItemManualEventReaderInterface
    {
        return new OrderItemManualEventReader(
            $this->createOrderStateMachineBuilder(),
        );
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Deleter\OmsEventTimeoutDeleterInterface
     */
    public function createOmsEventTimeoutDeleter(): OmsEventTimeoutDeleterInterface
    {
        return new OmsEventTimeoutDeleter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Deleter\OmsOrderItemStateHistoryDeleterInterface
     */
    public function createOmsOrderItemStateHistoryDeleter(): OmsOrderItemStateHistoryDeleterInterface
    {
        return new OmsOrderItemStateHistoryDeleter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Deleter\OmsTransitionLogDeleterInterface
     */
    public function createOmsTransitionLogDeleter(): OmsTransitionLogDeleterInterface
    {
        return new OmsTransitionLogDeleter($this->getEntityManager());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Util\TimeoutProcessorCollectionInterface
     */
    public function createTimeoutProcessorCollection(): TimeoutProcessorCollectionInterface
    {
        return new TimeoutProcessorCollection($this->getTimeoutProcessorPlugins());
    }

    /**
     * @return array<\Spryker\Zed\OmsExtension\Dependency\Plugin\OmsOrderMailExpanderPluginInterface>
     */
    public function getOmsOrderMailExpanderPlugins(): array
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGINS_OMS_ORDER_MAIL_EXPANDER);
    }

    /**
     * @return array<\Spryker\Zed\OmsExtension\Dependency\Plugin\OmsManualEventGrouperPluginInterface>
     */
    public function getOmsManualEventGrouperPlugins(): array
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGINS_OMS_MANUAL_EVENT_GROUPER);
    }

    /**
     * @deprecated Use {@link getOmsReservationAggregationPlugins()} instead.
     *
     * @return array<\Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationAggregationStrategyPluginInterface>
     */
    public function getReservationAggregationStrategyPlugins(): array
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGINS_RESERVATION_AGGREGATION);
    }

    /**
     * @return array<\Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationReaderStrategyPluginInterface>
     */
    public function getOmsReservationReaderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGINS_OMS_RESERVATION_READER_STRATEGY);
    }

    /**
     * @return array<\Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationAggregationPluginInterface>
     */
    public function getOmsReservationAggregationPlugins(): array
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGINS_OMS_RESERVATION_AGGREGATION);
    }

    /**
     * @return array<\Spryker\Zed\OmsExtension\Dependency\Plugin\OmsReservationWriterStrategyPluginInterface>
     */
    public function getOmsReservationWriterStrategyPlugins(): array
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGINS_OMS_RESERVATION_WRITER_STRATEGY);
    }

    /**
     * @return array<\Spryker\Zed\OmsExtension\Dependency\Plugin\ReservationPostSaveTerminationAwareStrategyPluginInterface>
     */
    public function getReservationHandlerTerminationAwareStrategyPlugins(): array
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGINS_RESERVATION_HANDLER_TERMINATION_AWARE_STRATEGY);
    }

    /**
     * @return array<\Spryker\Zed\OmsExtension\Dependency\Plugin\TimeoutProcessorPluginInterface>
     */
    public function getTimeoutProcessorPlugins(): array
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGINS_TIMEOUT_PROCESSOR);
    }

    /**
     * @return \Spryker\Zed\Oms\Dependency\Facade\OmsToMessageBrokerInterface
     */
    public function getMessageBrokerFacade()
    {
        return $this->getProvidedDependency(OmsDependencyProvider::FACADE_MESSAGE_BROKER);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OrderStatusChanged\OrderStatusChangedMessageSender
     */
    public function createOrderStatusChangedMessageSender(): OrderStatusChangedMessageSender
    {
        return new OrderStatusChangedMessageSender(
            $this->getMessageBrokerFacade(),
            $this->getStoreFacade(),
            $this->getSalesFacade(),
            $this->getConfig(),
            $this->getQueryContainer(),
        );
    }

    /**
     * @return array<\Spryker\Zed\OmsExtension\Dependency\Plugin\OmsEventTriggeredListenerPluginInterface>
     */
    public function getOmsEventTriggeredListenerPlugins(): array
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGINS_OMS_EVENT_TRIGGERED_LISTENER);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Reader\ProcessCacheReaderInterface
     */
    public function createProcessCacheReader(): ProcessCacheReaderInterface
    {
        return new ProcessCacheReader($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\Writer\ProcessCacheWriterInterface
     */
    public function createProcessCacheWriter(): ProcessCacheWriterInterface
    {
        return new ProcessCacheWriter(
            $this->getConfig(),
            $this->createProcessCacheReader(),
        );
    }

    /**
     * @return \Spryker\Zed\OmsExtension\Dependency\Plugin\OmsLockPluginInterface|null
     */
    public function getLockPlugin(): ?OmsLockPluginInterface
    {
        return $this->getProvidedDependency(OmsDependencyProvider::PLUGIN_LOCK);
    }
}
