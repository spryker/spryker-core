<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
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

        $userTransfer = $this->getUserTransfer($cmsVersionTransfer->getFkUser());
        $cmsVersionTransfer->setFirstName($userTransfer->getFirstName());
        $cmsVersionTransfer->setLastName($userTransfer->getLastName());

        return $cmsVersionTransfer;
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUserTransfer(int $idUser): UserTransfer
    {
        $userCriteriaTransfer = $this->createUserCriteriaTransfer($idUser);
        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);

        return $userCollectionTransfer->getUsers()->getIterator()->current();
    }

    /**
     * @param int $idUser
     *
     * @return \Generated\Shared\Transfer\UserCriteriaTransfer
     */
    protected function createUserCriteriaTransfer(int $idUser): UserCriteriaTransfer
    {
        $userConditionsTransfer = (new UserConditionsTransfer())
            ->addIdUser($idUser)
            ->setThrowUserNotFoundException(true);

        return (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);
    }
}
