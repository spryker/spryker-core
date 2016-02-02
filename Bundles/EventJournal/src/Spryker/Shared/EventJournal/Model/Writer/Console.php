<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Shared\EventJournal\Model\Writer;

use Spryker\Shared\EventJournal\Model\EventInterface;

class Console extends AbstractWriter
{

    const TYPE = 'console';

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
     *
     * @return bool
     */
    public function write(EventInterface $event)
    {
        echo json_encode($event->getFields(), JSON_PRETTY_PRINT);

        return true;
    }

}
