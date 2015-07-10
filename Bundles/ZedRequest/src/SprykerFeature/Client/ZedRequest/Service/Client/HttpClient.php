<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest\Service\Client;

use SprykerFeature\Shared\ZedRequest\Client\AbstractHttpClient;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestConfig;

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
     * @return ZedRequestConfig
     */
    protected function getSettings()
    {
        return $this->factory->createZedRequestConfig($this->authClient);
    }

}
