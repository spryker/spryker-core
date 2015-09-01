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
            $this->getFilePath(),
            $this->getJsonEntry($event),
            FILE_APPEND | LOCK_EX
        );
    }

    protected function getFilePath(){
        $path = \SprykerFeature_Shared_Library_Data::getLocalCommonPath('lumberjack');
        $path . 'lumberjack-' . date('Y-m-d') . '.log';
        return $path;
    }

    protected function getJsonEntry(EventInterface $event) {
        return json_encode($event->getFields()) . "\n";
    }

}
