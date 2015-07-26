<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service\Action;

use Generated\Shared\Customer\CustomerInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class AbstractActionFactory
{
    const WISHLIST_SESSION_IDENTIFIER = 'wishlist session identifier';

    protected $url_pattern = "/wishlist/gateway/%s";

    protected $transferObject;

    protected $session;

    protected $client;

    protected $customerTransfer;

    protected $response;

    /**
     * @param SessionInterface $session
     * @param ZedRequestClient $client
     * @param CustomerInterface|null $customerTransfer
     */
    public function __construct(SessionInterface $session, ZedRequestClient $client, CustomerInterface $customerTransfer = null)
    {
        $this->session = $session;

        $this->client = $client;

        $this->customerTransfer = $customerTransfer;
    }


    public function execute()
    {
        $this->handleSession();

        if (null !== $this->customerTransfer) {
            $this->handleZed();
        }

        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    protected function setResponse(TransferInterface $response = null)
    {
        $this->response = $response;
    }

    /**
     * @param string $action
     *
     * @return string
     */
    protected function getUrl($action)
    {
        return sprintf($this->url_pattern, $action);
    }

    /**
     * @param TransferInterface $transfer
     * @throws \InvalidArgumentException
     * @return $this
     */
    abstract public function setTransferObject(TransferInterface $transfer);

    abstract protected function handleSession();

    abstract protected function handleZed();


}
