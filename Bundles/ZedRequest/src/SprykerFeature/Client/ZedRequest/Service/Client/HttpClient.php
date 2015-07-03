<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest\Service\Client;

use SprykerFeature\Shared\ZedRequest\Client\AbstractHttpClient;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestSettings;

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
