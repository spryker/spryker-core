<?php
/**
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace Spryker\Shared\EventJournal\Model;

use Spryker\Shared\EventJournal\Model\Collector\DataCollectorInterface;
use Spryker\Shared\EventJournal\Model\Writer\WriterInterface;

interface EventJournalInterface
{

    /**
     * @param DataCollectorInterface $dataCollector
     */
    public function setDataCollector(DataCollectorInterface $dataCollector);

    /**
     * @param EventInterface $event
     */
    public function applyCollectors(EventInterface $event);

    /**
     * @param EventInterface $event
     */
    public function saveEvent(EventInterface $event);

    /**
     * @param WriterInterface $writer
     */
    public function setEventWriter(WriterInterface $writer);

}
