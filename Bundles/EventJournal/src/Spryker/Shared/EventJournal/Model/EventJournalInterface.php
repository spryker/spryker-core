<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model;

use Spryker\Shared\EventJournal\Model\Collector\DataCollectorInterface;
use Spryker\Shared\EventJournal\Model\Writer\WriterInterface;

interface EventJournalInterface
{

    /**
     * @param \Spryker\Shared\EventJournal\Model\Collector\DataCollectorInterface $dataCollector
     *
     * @return void
     */
    public function setDataCollector(DataCollectorInterface $dataCollector);

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
     *
     * @return void
     */
    public function applyCollectors(EventInterface $event);

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
     *
     * @return void
     */
    public function saveEvent(EventInterface $event);

    /**
     * @param \Spryker\Shared\EventJournal\Model\Writer\WriterInterface $writer
     *
     * @return void
     */
    public function setEventWriter(WriterInterface $writer);

}
