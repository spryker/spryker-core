<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\ZedRequest\Client;

use SprykerEngine\Shared\Transfer\TransferInterface;

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
    private $metaTransfers = [

    ];

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $name
     * @param mixed $metaTransfer
     *
     * @return self
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
     * @param string $url
     * @param TransferInterface $object
     * @param int|null $timeoutInSeconds (optional) default: null
     * @param bool $isBackgroundRequest (optional) default: false
     *
     * @return TransferInterface
     */
    public function call($url, TransferInterface $object, $timeoutInSeconds = null, $isBackgroundRequest = false)
    {
        $this->lastResponse = null;
        $this->lastResponse = $this->httpClient->request(
            $url,
            $object,
            $this->prepareAndGetMetaTransfers(),
            $timeoutInSeconds,
            $isBackgroundRequest
        );

        return $this->lastResponse->getTransfer();
    }

    /**
     * @throws \BadMethodCallException
     *
     * @return ResponseInterface
     */
    public function getLastResponse()
    {
        if ($this->lastResponse === null) {
            throw new \BadMethodCallException('No response available yet');
        }

        return $this->lastResponse;
    }

}
