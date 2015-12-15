<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Client\EventJournal;

use Spryker\Shared\Config;
use Spryker\Shared\EventJournal\Model\Collector\AbstractDataCollector;
use Spryker\Shared\EventJournal\Model\Collector\DataCollectorInterface;
use Spryker\Shared\Yves\YvesConfig;

class YvesDataCollector extends AbstractDataCollector implements DataCollectorInterface
{

    const TYPE = 'yves';

    /**
     * @return array
     */
    public function getData()
    {
        return [
            'session_id' => session_id(),
            'device_id' => $this->getDeviceId(),
            'visitor_id' => $this->getVisitorId()
        ];
    }

    protected function getDeviceId() {
        $key = Config::get(YvesConfig::YVES_COOKIE_DEVICE_ID_NAME);
        return $_COOKIE[$key] ?: null;
    }

    protected function getVisitorId() {
        $key =  Config::get(YvesConfig::YVES_COOKIE_VISITOR_ID_NAME);
        return $_COOKIE[$key] ?: null;
    }

}
