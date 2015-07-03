<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerEngine\Shared\Transfer\TransferInterface;

/**
 * @method ZedRequestDependencyContainer getDependencyContainer()
 */
class ZedRequestClient extends AbstractClient
{

    /**
     * @return Client\ZedClient
     */
    private function getClient()
    {
        return $this->getDependencyContainer()->createClient();
    }

    /**
     * @param $url
     * @param TransferInterface $object
     * @param null $timeoutInSeconds
     * @param bool|false $isBackgroundRequest
     *
     * @return TransferInterface
     */
    public function call($url, TransferInterface $object, $timeoutInSeconds = null, $isBackgroundRequest = false)
    {
        return $this->getClient()->call($url, $object, $timeoutInSeconds, $isBackgroundRequest);
    }
}
