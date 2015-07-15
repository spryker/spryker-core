<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Kernel\Communication\Plugin\Fixture;

use SprykerFeature\Shared\Library\Communication\Request;
use SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\TransferServer as CoreTransferServer;
use SprykerFeature\Zed\ZedRequest\Business\Client\Request as ZedRequest;

class TransferServer extends CoreTransferServer
{

    /**
     * @var Request
     */
    private $fixtureRequest;

    /**
     * @param Request $request
     *
     * @return $this
     */
    public function setFixtureRequest(Request $request)
    {
        $this->fixtureRequest = $request;

        return $this;
    }

    /**
     * @return ZedRequest
     */
    public function getRequest()
    {
        if (isset($this->fixtureRequest)) {
            return $this->fixtureRequest;
        }

        return parent::getRequest();
    }

}
