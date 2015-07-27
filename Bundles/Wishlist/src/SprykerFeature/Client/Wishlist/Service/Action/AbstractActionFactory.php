<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service\Action;

use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerEngine\Shared\Transfer\TransferInterface;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class AbstractActionFactory
{
    /**
     * @var null|string
     */
    private static $wishlistSessionID = null;

    /**
     * @var string
     */
    protected $urlPattern = '/wishlist/gateway/%s';

    /**
     * @var TransferInterface
     */
    protected $transferObject;

    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var ZedRequestClient
     */
    protected $client;

    /**
     * @var CustomerInterface|null
     */
    protected $customerTransfer;

    /**
     * @var WishlistInterface
     */
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

    /**
     * @return string
     */
    public static function getWishlistSessionID()
    {
        if (null === self::$wishlistSessionID) {

            self::$wishlistSessionID = APPLICATION_ENV . '_wishlist';

        }

        return self::$wishlistSessionID;
    }

    /**
     * @return AbstractActionFactory
     */
    public function execute()
    {
        $this->synchronizeSessionLayer();

        if (null !== $this->customerTransfer) {
            $this->synchronizePersistingLayer();
        }

        return $this;
    }

    /**
     * @return WishlistInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param WishlistInterface|null $response
     */
    protected function setResponse(WishlistInterface $response = null)
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
        return sprintf($this->urlPattern, $action);
    }

    /**
     * @param TransferInterface $transfer
     * @throws \InvalidArgumentException
     * @return $this
     */
    abstract public function setTransferObject(TransferInterface $transfer);

    abstract protected function synchronizeSessionLayer();

    abstract protected function synchronizePersistingLayer();


}
