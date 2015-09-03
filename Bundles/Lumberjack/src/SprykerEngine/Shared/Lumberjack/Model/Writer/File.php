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
     * @var array
     */
    static $fileHandles = [];

    static $preferedHandle;

    /**
     * @inheritdoc
     */
    public function write(EventInterface $event)
    {
        $output = $this->getJsonEntry($event);
        if ($output === '') {
            return false;
        }

        return $this->optimisticRandomWrite($output);
    }

    /**
     * @param string $content
     *
     * @return bool
     */
    protected function optimisticRandomWrite($content)
    {
        for ($i = 0; $i <= 9; $i++) {
            $handle = $this->getOrCreateRandomFileHandle();
            if ($this->acquireNonBlockingLock($handle)) {
                self::$preferedHandle = $handle;
                // @todo add silent exception when this operation fails
                fwrite($handle, $content);
                $this->unlock($handle);

                return true;
            }
            self::$preferedHandle = null;
        }

        return false;
    }

    /**
     * @return mixed
     */
    protected function getOrCreateRandomFileHandle()
    {
        if(null !== self::$preferedHandle) {
            return self::$preferedHandle;
        }
        $fileName = $this->getRandomFileName();
        if (!isset(static::$fileHandles[$fileName])) {
            $fileHandle = fopen($fileName, 'a');
            static::$fileHandles[$fileName] = $fileHandle;
        }

        return static::$fileHandles[$fileName];
    }

    /**
     * @return string
     */
    protected function getRandomFileName()
    {
        $path = \SprykerFeature_Shared_Library_Data::getLocalCommonPath('lumberjack');
        $path .= sprintf(
            '%s.%s.%d.log',
            'lumberjack',
            date('Y-m-d'),
            $this->getRandomizedFileIndex()
        );

        return $path;
    }

    /**
     * @return int
     */
    protected function getRandomizedFileIndex()
    {
        return rand(0, 9);
    }

    /**
     * @param EventInterface $event
     *
     * @return string
     */
    protected function getJsonEntry(EventInterface $event)
    {
        $json = json_encode($event->getFields(), JSON_PRETTY_PRINT);
        if ($json === false) {
            return '';
        }

        return $json . "\n";
    }

    /**
     * @param $handle
     *
     * @return bool
     */
    protected function acquireNonBlockingLock($handle)
    {
        return flock($handle, LOCK_EX | LOCK_NB);
    }

    /**
     * @param $handle
     */
    protected function unlock($handle)
    {
        flock($handle, LOCK_UN);
    }

}
