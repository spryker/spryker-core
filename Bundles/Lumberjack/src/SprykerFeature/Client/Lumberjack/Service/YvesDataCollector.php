<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Client\Lumberjack\Service;

use SprykerEngine\Shared\Lumberjack\Model\Collector\DataCollectorInterface;

class YvesDataCollector implements DataCollectorInterface
{

    /**
     * @return array
     */
    public function getData()
    {
        return ['session_id' => sha1(session_id())];
    }

}
