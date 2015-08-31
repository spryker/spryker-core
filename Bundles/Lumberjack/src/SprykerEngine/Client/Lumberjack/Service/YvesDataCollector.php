<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Client\Lumberjack\Service;

use SprykerEngine\Shared\Lumberjack\Model\DataCollectorInterface;

class YvesDataCollector implements DataCollectorInterface
{

    public function getData()
    {
        return ['session_id' => substr(session_id(), 0, 4)];
    }

}
