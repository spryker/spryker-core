<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;

use SprykerEngine\Shared\Config;
use SprykerEngine\Shared\Lumberjack\LumberjackConfig;
use SprykerEngine\Shared\Lumberjack\Model\Writer\WriterInterface;
use SprykerEngine\Shared\Lumberjack\Model\Collector\DataCollectorInterface;

abstract class AbstractEventJournal
{

    /**
     * @var DataCollectorInterface[]
     */
    private $dataCollectors = [];

    /**
     * @var WriterInterface[]
     */
    private $eventWriters = [];

    public function __construct()
    {
        $this->addConfiguredCollectors();
        $this->addConfiguredWriters();
    }

    protected function addConfiguredCollectors()
    {
        $collectors = Config::get(LumberjackConfig::COLLECTORS);
        foreach($collectors[APPLICATION] as $collector) {
            $this->addDataCollector(new $collector);
        }
    }

    protected function addConfiguredWriters()
    {
        $writers = Config::get(LumberjackConfig::WRITERS);
        foreach($writers[APPLICATION] as $writer) {
            $this->addEventWriter(new $writer);
        }
    }

    /**
     * @param DataCollectorInterface $dataCollector
     */
    public function addDataCollector(DataCollectorInterface $dataCollector)
    {
        $this->dataCollectors[get_class($dataCollector)] = $dataCollector;
    }

    /**
     * @param EventInterface $event
     */
    public function applyCollectors(EventInterface $event)
    {
        foreach ($this->dataCollectors as $collector) {
            $event->addFields($collector->getData());
        }
    }

    /**
     * @param EventInterface $event
     */
    public function saveEvent(EventInterface $event)
    {
        $this->applyCollectors($event);
        $this->writeEvent($event);
    }

    /**
     * @param WriterInterface $writer
     */
    public function addEventWriter(WriterInterface $writer)
    {
        $this->eventWriters[get_class($writer)] = $writer;
    }

    /**
     * @param EventInterface $event
     */
    protected function writeEvent(EventInterface $event)
    {
        foreach ($this->eventWriters as $writer) {
            $writer->write($event);
        }
    }
}
