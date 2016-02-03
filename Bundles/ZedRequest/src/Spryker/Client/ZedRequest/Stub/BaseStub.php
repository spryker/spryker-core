<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\ZedRequest\Stub;

use Spryker\Client\ZedRequest\ZedRequestClient;

class BaseStub
{

    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
     */
    public function getInfoMessages()
    {
        return $this->zedStub->getLastResponseInfoMessages();
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
     */
    public function getSuccessMessages()
    {
        return $this->zedStub->getLastResponseSuccessMessages();
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\Message[]
     */
    public function getErrorMessages()
    {
        return $this->zedStub->getLastResponseErrorMessages();
    }

}
