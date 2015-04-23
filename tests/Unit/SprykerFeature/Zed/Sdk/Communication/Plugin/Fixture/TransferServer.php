<?php

namespace Unit\SprykerFeature\Zed\Sdk\Communication\Plugin\Fixture;

use SprykerFeature\Shared\Library\Communication\Request;
use SprykerFeature\Zed\Application\Communication\Plugin\TransferObject\TransferServer as CoreTransferServer;

class TransferServer extends CoreTransferServer
{

    private $fixtureRequest;

    /**
     * @param Request $request
     *
     * @return void
     */
    public function setFixtureRequest(Request $request)
    {
        $this->fixtureRequest = $request;
    }

    /**
     * @return \SprykerFeature\Zed\ZedRequest\Business\Client\Request
     */
    public function getRequest()
    {
        if (isset($this->fixtureRequest)) {
            return $this->fixtureRequest;
        }

        return parent::getRequest();
    }
}
 