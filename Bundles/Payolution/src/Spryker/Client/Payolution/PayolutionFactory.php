<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Payolution;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Payolution\Session\PayolutionSession;
use Spryker\Client\Payolution\Zed\PayolutionStub;
use Spryker\Client\Payolution\Zed\PayolutionStubInterface;

class PayolutionFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Payolution\Session\PayolutionSession
     */
    public function createPayolutionSession()
    {
        return new PayolutionSession($this->createSessionClient());
    }

    /**
     * @return \Spryker\Client\Payolution\Zed\PayolutionStubInterface
     */
    public function createPayolutionStub()
    {
        return new PayolutionStub($this->createZedRequestClient());
    }

}
