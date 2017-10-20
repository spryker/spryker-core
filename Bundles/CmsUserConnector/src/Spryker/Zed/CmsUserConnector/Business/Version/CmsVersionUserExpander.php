<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\CmsUserConnector\Dependency\Facade\CmsUserConnectorToUserInterface;

class CmsVersionUserExpander implements CmsVersionUserExpanderInterface
{
    /**
     * @var \Spryker\Zed\CmsUserConnector\Dependency\Facade\CmsUserConnectorToUserInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\CmsUserConnector\Dependency\Facade\CmsUserConnectorToUserInterface $userFacade
     */
    public function __construct(CmsUserConnectorToUserInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function expandCmsVersionTransferWithUser(CmsVersionTransfer $cmsVersionTransfer)
    {
        if ($cmsVersionTransfer->getFkUser() === null) {
            return $cmsVersionTransfer;
        }

        $userTransfer = $this->userFacade->getUserById($cmsVersionTransfer->getFkUser());
        $cmsVersionTransfer->setFirstName($userTransfer->getFirstName());
        $cmsVersionTransfer->setLastName($userTransfer->getLastName());

        return $cmsVersionTransfer;
    }
}
