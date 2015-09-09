<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model\Writer;

use SprykerEngine\Shared\Lumberjack\Model\EventInterface;

class Console extends AbstractWriter
{

    const TYPE = 'console';

    public function write(EventInterface $event)
    {
        print json_encode($event->getFields(), JSON_PRETTY_PRINT);

        return true;
    }

}
