<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;
use SprykerEngine\Client\Lumberjack\Service\YvesDataCollector;
/**
 * DO NOT USE THIS CLASS in general. It is meant to be used in the bootsrapping when it would be inconvenient to
 * determine every time whether to use the Yves or Zed Journal.
 *
 * @package SprykerEngine\Shared\Lumberjack\Model
 */
class SharedEventJournal extends AbstractEventJournal
{

    /**
     * DO NOT USE THIS CLASS in general. It is meant to be used in the bootsrapping when it would be inconvenient to
     * determine every time whether to use the Yves or Zed Journal.
     */
    public function __construct()
    {
        parent::__construct();
        if (APPLICATION == 'YVES') {
            $this->addDataCollector(new YvesDataCollector());
        }
        if (APPLICATION == 'ZED') {
            //
        }
    }

}
