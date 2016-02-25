<?php

namespace Spryker\Zed\Sales\Dependency\Facade;

interface SalesToUserInterface
{

    /**
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    public function getCurrentUser();

}
