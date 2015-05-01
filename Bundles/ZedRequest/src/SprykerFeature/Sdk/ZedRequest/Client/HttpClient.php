<?php

namespace SprykerFeature\Sdk\ZedRequest\Client;

use SprykerFeature\Shared\ZedRequest\Client\AbstractHttpClient;
use SprykerFeature\Sdk\ZedRequest\ZedRequestSettings;

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
