<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerFeature\Client\ZedRequest\Service\Client\ZedClient;
use SprykerFeature\Shared\ZedRequest\Client\Message;

/**
 * @method ZedRequestDependencyContainer getDependencyContainer()
 */
class ZedRequestClient extends AbstractClient
{

    /**
     * @var ZedClient
     */
    private $zedClient;

    /**
     * @return ZedClient
     */
    private function getClient()
    {
        if ($this->zedClient === null) {
            $this->zedClient = $this->getDependencyContainer()->createClient();
        }

        return $this->zedClient;
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

    /**
     * @return Message[]
     */
    public function getLastResponseInfoMessages()
    {
        return $this->getClient()->getLastResponse()->getInfoMessages();
    }

    /**
     * @return Message[]
     */
    public function getLastResponseErrorMessages()
    {
        return $this->getClient()->getLastResponse()->getErrorMessages();
    }

    /**
     * @return Message[]
     */
    public function getLastResponseSuccessMessages()
    {
        return $this->getClient()->getLastResponse()->getSuccessMessages();
    }

}
