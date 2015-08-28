<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Client\Lumberjack\Service;

use SprykerEngine\Shared\Lumberjack\Model\AbstractEventJournal;

class EventJournalClient extends AbstractEventJournal
{
    public function __construct()
    {
        parent::__construct();
        $this->addDataCollector(new YvesDataCollector());
    }
}
