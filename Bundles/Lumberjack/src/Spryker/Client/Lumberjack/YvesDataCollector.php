<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Client\Lumberjack;

use Spryker\Shared\Lumberjack\Model\Collector\AbstractDataCollector;
use Spryker\Shared\Lumberjack\Model\Collector\DataCollectorInterface;

/**
 * @deprecated Lumberjack is deprecated use EventJournal instead.
 */
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
