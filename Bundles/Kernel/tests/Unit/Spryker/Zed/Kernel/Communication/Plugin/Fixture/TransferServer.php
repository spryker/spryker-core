<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\Communication\Plugin\Fixture;

use Spryker\Shared\Library\Communication\Request;
use Spryker\Zed\Application\Communication\Plugin\TransferObject\TransferServer as CoreTransferServer;

class TransferServer extends CoreTransferServer
{

    /**
     * @var \Spryker\Shared\Library\Communication\Request
     */
    private $fixtureRequest;

    /**
     * @param \Spryker\Shared\Library\Communication\Request $request
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
