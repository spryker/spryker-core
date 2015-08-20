<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Zed\Lumberjack;

use Symfony\Component\Config\Definition\Exception\Exception;

class LumberjackConfig {

    const WRITER_CONSOLE = 'Console';

    const WRITER_FILE = 'File';

    const WRITER_NEWRELIC = 'Newrelic';

    /**
     * @var array
     */
    static protected $availableWriters = [
        self::WRITER_CONSOLE,
        self::WRITER_FILE,
        self::WRITER_NEWRELIC
    ];

    /**
     * @var array
     */
    static private $activatedWriters = [];

    /**
     * @param $writer
     */
    static function activateWriter($writer) {
        if(!in_array($writer, static::$availableWriters)) {
            throw new Exception(sprintf("Cannot find Writer %s", $writer));
        }

        self::$activatedWriters[$writer] = $writer;

    }

    /**
     * @return array
     */
    static function getActivatedWriters() {
        return self::$activatedWriters;
    }
}
