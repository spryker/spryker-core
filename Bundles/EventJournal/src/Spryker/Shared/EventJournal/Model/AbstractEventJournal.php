<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Shared\EventJournal\Model;

use Spryker\Shared\Config;
use Spryker\Shared\EventJournal\EventJournalConstants;
use Spryker\Shared\EventJournal\Model\Collector\DataCollectorInterface;
use Spryker\Shared\EventJournal\Model\Filter\FilterInterface;
use Spryker\Shared\EventJournal\Model\Writer\WriterInterface;

abstract class AbstractEventJournal implements EventJournalInterface
{

    /**
     * @var DataCollectorInterface[]
     */
    private $dataCollectors = [];

    /**
     * @var WriterInterface[]
     */
    private $eventWriters = [];

    /**
     * @var FilterInterface[]
     */
    private $eventFilters = [];

    public function __construct()
    {
        $this->addConfiguredCollectors();
        $this->addConfiguredWriters();
        $this->addConfiguredFilters();
    }

    /**
     * @return void
     */
    protected function addConfiguredCollectors()
    {
        $collectors = Config::get(EventJournalConstants::COLLECTORS, []);
        $collectorOptions = Config::get(EventJournalConstants::COLLECTOR_OPTIONS, []);
        foreach ($collectors[APPLICATION] as $collector) {
            $collectorConfig = isset($collectorOptions[$collector]) ? $collectorOptions[$collector] : [];
            $this->setDataCollector(new $collector($collectorConfig));
        }
    }

    /**
     * @return void
     */
    protected function addConfiguredWriters()
    {
        $writers = Config::get(EventJournalConstants::WRITERS, []);
        $writerOptions = Config::get(EventJournalConstants::WRITER_OPTIONS, []);
        foreach ($writers[APPLICATION] as $writer) {
            $writerConfig = isset($writerOptions[$writer]) ? $writerOptions[$writer] : [];
            $this->setEventWriter(new $writer($writerConfig));
        }
    }

    /**
     * @return void
     */
    protected function addConfiguredFilters()
    {
        $filters = Config::get(EventJournalConstants::FILTERS, []);
        $filterOptions = Config::get(EventJournalConstants::FILTER_OPTIONS, []);
        foreach ($filters[APPLICATION] as $filter) {
            $filterConfig = isset($filterOptions[$filter]) ? $filterOptions[$filter] : [];
            $this->setFilter(new $filter($filterConfig));
        }
    }

    /**
     * @param DataCollectorInterface $dataCollector
     *
     * @return void
     */
    public function setDataCollector(DataCollectorInterface $dataCollector)
    {
        $this->dataCollectors[$dataCollector->getType()] = $dataCollector;
    }

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function applyCollectors(EventInterface $event)
    {
        foreach ($this->dataCollectors as $collector) {
            $event->setFields($collector->getData());
        }
    }

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event)
    {
        $this->applyCollectors($event);
        $this->applyFilters($event);
        $this->writeEvent($event);
    }

    /**
     * @param WriterInterface $writer
     *
     * @return void
     */
    public function setEventWriter(WriterInterface $writer)
    {
        $this->eventWriters[$writer->getType()] = $writer;
    }

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    protected function writeEvent(EventInterface $event)
    {
        foreach ($this->eventWriters as $writer) {
            $writer->write($event);
        }
    }

    /**
     * @param EventInterface $event
     *
     * @return void
     */
    protected function applyFilters(EventInterface $event)
    {
        foreach ($this->eventFilters as $filter) {
            $filter->filter($event);
        }
    }

    /**
     * @param FilterInterface $filter
     *
     * @return void
     */
    protected function setFilter(FilterInterface $filter)
    {
        $this->eventFilters[$filter->getType()] = $filter;
    }

}
