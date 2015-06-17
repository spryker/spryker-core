<?php

namespace SprykerFeature\Client\ZedRequest\Client;

use SprykerFeature\Shared\ZedRequest\Client\AbstractHttpClient;
use SprykerFeature\Client\ZedRequest\ZedRequestSettings;

class HttpClient extends AbstractHttpClient
{
    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->getSettings()->getHeaders();
    }

    /**
     * @return ZedRequestSettings
     */
    protected function getSettings()
    {
        return $this->factory->createZedRequestSettings($this->locator);
    }
}
