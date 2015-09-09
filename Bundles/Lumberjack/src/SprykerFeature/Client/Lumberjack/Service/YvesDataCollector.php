<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerFeature\Client\Lumberjack\Service;

use SprykerEngine\Shared\Lumberjack\Model\Collector\AbstractDataCollector;
use SprykerEngine\Shared\Lumberjack\Model\Collector\DataCollectorInterface;

class YvesDataCollector extends AbstractDataCollector implements DataCollectorInterface
{

    const TYPE = 'yves';

    /**
     * @return array
     */
    public function getData()
    {
        return ['session_id' => sha1(session_id())];
    }

}
