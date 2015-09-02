<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model\Writer;

use SprykerEngine\Shared\Lumberjack\Model\EventInterface;

class File extends AbstractWriter
{

    /**
     * @inheritdoc
     */
    public function write(EventInterface $event)
    {
        $output = $this->getJsonEntry($event);
        if ($output === '') {
            return false;
        }

        return (bool)file_put_contents(
            $this->getFilePath(),
            $output,
            FILE_APPEND | LOCK_EX
        );
    }

    /**
     * @return string
     */
    protected function getFilePath()
    {
        $path = \SprykerFeature_Shared_Library_Data::getLocalCommonPath('lumberjack');
        $path .= 'lumberjack-' . date('Y-m-d') . '.log';

        return $path;
    }

    /**
     * @param EventInterface $event
     *
     * @return string
     */
    protected function getJsonEntry(EventInterface $event)
    {
        $json = json_encode($event->getFields());
        if ($json === false) {
            return '';
        }

        return $json . "\n";
    }

}
