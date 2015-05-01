<?php

namespace SprykerFeature\Shared\ZedRequest\Client;

use SprykerFeature\Shared\Library\TransferObject\TransferInterface;

abstract class AbstractZedClient
{

    /**
     * @var HttpClientInterface
     */
    private $httpClient = null;

    /**
     * @var ResponseInterface
     */
    private $lastResponse = null;

    /**
     * @var TransferInterface[]|\Closure[]
     */
    private $metaTransfers = array(

    );

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $name
     * @param $metaTransfer
     * @return $this
     */
    public function addMetaTransfer($name, $metaTransfer)
    {
        $this->metaTransfers[$name] = $metaTransfer;
        return $this;
    }

    /**
     * @return TransferInterface[]
     */
    private function prepareAndGetMetaTransfers()
    {
        foreach ($this->metaTransfers as $name => $transfer) {
            if (is_object($transfer) && method_exists($transfer, '__invoke')) {
                $this->metaTransfers[$name] = $transfer();
            }
        }
        return $this->metaTransfers;
    }

    /**
     * @param $url
     * @param TransferInterface $object
     * @param $timeoutInSeconds (optional) default: null
     * @param $isBackgroundRequest (optional) default: false
     * @return TransferInterface
     */
    public function call($url, TransferInterface $object, $timeoutInSeconds = null, $isBackgroundRequest = false)
    {
        $this->lastResponse = null;
        $this->lastResponse =  $this->httpClient->request(
            $url,
            $object,
            $this->prepareAndGetMetaTransfers(),
            $timeoutInSeconds,
            $isBackgroundRequest
        );

        return $this->lastResponse->getTransfer();
    }

    /**
     * @return ResponseInterface
     * @throws \BadMethodCallException
     */
    public function getLastResponse()
    {
        if ($this->lastResponse === null) {
            throw new \BadMethodCallException('No response available yet');
        }
        return $this->lastResponse;
    }
}
