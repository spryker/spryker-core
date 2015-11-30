<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\ZedRequest\Service\Stub;

use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;
use SprykerFeature\Shared\ZedRequest\Client\Message;

class BaseStub
{
    /**
     * @var ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @return Message[]
     */
    public function getInfoMessages()
    {
        return $this->zedStub->getLastResponseInfoMessages();
    }

    /**
     * @return Message[]
     */
    public function getSuccessMessages()
    {
        return $this->zedStub->getLastResponseSuccessMessages();
    }

    /**
     * @return Message[]
     */
    public function getErrorMessages()
    {
        return $this->zedStub->getLastResponseErrorMessages();
    }
}
