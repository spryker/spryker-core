<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model;

use Spryker\Shared\Config\Config;
use Spryker\Shared\EventJournal\EventJournalConstants;
use Spryker\Shared\EventJournal\Model\Collector\DataCollectorInterface;
use Spryker\Shared\EventJournal\Model\Filter\FilterInterface;
use Spryker\Shared\EventJournal\Model\Writer\WriterInterface;

abstract class AbstractEventJournal implements EventJournalInterface
{

    /**
     * @var \Spryker\Shared\EventJournal\Model\Collector\DataCollectorInterface[]
     */
    private $dataCollectors = [];

    /**
     * @var \Spryker\Shared\EventJournal\Model\Writer\WriterInterface[]
     */
    private $eventWriters = [];

    /**
     * @var \Spryker\Shared\EventJournal\Model\Filter\FilterInterface[]
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
     * @param \Spryker\Shared\EventJournal\Model\Collector\DataCollectorInterface $dataCollector
     *
     * @return void
     */
    public function setDataCollector(DataCollectorInterface $dataCollector)
    {
        $this->dataCollectors[$dataCollector->getType()] = $dataCollector;
    }

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
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
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
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
     * @param \Spryker\Shared\EventJournal\Model\Writer\WriterInterface $writer
     *
     * @return void
     */
    public function setEventWriter(WriterInterface $writer)
    {
        $this->eventWriters[$writer->getType()] = $writer;
    }

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
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
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
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
     * @param \Spryker\Shared\EventJournal\Model\Filter\FilterInterface $filter
     *
     * @return void
     */
    protected function setFilter(FilterInterface $filter)
    {
        $this->eventFilters[$filter->getType()] = $filter;
    }

}
