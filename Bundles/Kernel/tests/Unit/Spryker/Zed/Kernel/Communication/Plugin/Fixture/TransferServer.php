<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\Communication\Plugin\Fixture;

use Spryker\Zed\Application\Communication\Plugin\TransferObject\TransferServer as CoreTransferServer;
use Spryker\Zed\ZedRequest\Business\Client\Request;

class TransferServer extends CoreTransferServer
{

    /**
     * @var \Spryker\Zed\ZedRequest\Business\Client\Request
     */
    private $fixtureRequest;

    /**
     * @param \Spryker\Zed\ZedRequest\Business\Client\Request $request
     *
     * @return $this
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
