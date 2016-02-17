<?php

namespace Spryker\Zed\Sales\Dependency\Facade;

interface SalesToUserBridgeInterface
{

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser();

}
