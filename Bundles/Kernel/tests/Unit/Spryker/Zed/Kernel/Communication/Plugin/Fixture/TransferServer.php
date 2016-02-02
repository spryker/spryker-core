<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Communication\Plugin\Fixture;

use Spryker\Shared\Library\Communication\Request;
use Spryker\Zed\Application\Communication\Plugin\TransferObject\TransferServer as CoreTransferServer;

class TransferServer extends CoreTransferServer
{

    /**
     * @var Request
     */
    private $fixtureRequest;

    /**
     * @param \Spryker\Shared\Library\Communication\Request $request
     *
     * @return self
     */
    public function setFixtureRequest(Request $request)
    {
        $this->fixtureRequest = $request;

        return $this;
    }

    /**
     * @return \Spryker\Zed\ZedRequest\Business\Client\Request
     */
    public function getRequest()
    {
        if (isset($this->fixtureRequest)) {
            return $this->fixtureRequest;
        }

        return parent::getRequest();
    }

}
