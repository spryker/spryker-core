<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model\Writer;

use SprykerEngine\Shared\Lumberjack\Model\EventInterface;

class File extends AbstractWriter
{

    public function write(EventInterface $event)
    {
        return file_put_contents(
            $this->options['file_path'],
            json_encode($event->getFields()),
            FILE_APPEND | LOCK_EX
        );
    }

}
