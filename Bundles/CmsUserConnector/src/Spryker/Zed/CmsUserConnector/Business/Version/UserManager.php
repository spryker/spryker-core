<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsVersion;
use Orm\Zed\User\Persistence\SpyUser;
use Spryker\Zed\CmsUserConnector\Dependency\Facade\CmsUserConnectorToUserInterface;
use Spryker\Zed\CmsUserConnector\Dependency\QueryContainer\CmsUserConnectorToCmsQueryContainerInterface;

class UserManager implements UserManagerInterface
{

    /**
     * @var CmsUserConnectorToUserInterface
     */
    protected $userFacade;

    /**
     * @var CmsUserConnectorToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param CmsUserConnectorToUserInterface $userFacade
     * @param CmsUserConnectorToCmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(CmsUserConnectorToUserInterface $userFacade, CmsUserConnectorToCmsQueryContainerInterface $cmsQueryContainer)
    {
        $this->userFacade = $userFacade;
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsVersionTransfer
     */
    public function updateCmsVersion(CmsVersionTransfer $cmsVersionTransfer)
    {
        $idUser = $this->userFacade->getCurrentUser()->getIdUser();
        $cmsVersionEntity = $this->updateCmsVersionUserId($cmsVersionTransfer, $idUser);
        $cmsVersionTransfer->fromArray($cmsVersionEntity->toArray(), true);

        return $cmsVersionTransfer;
    }

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     *
     * @return CmsVersionTransfer
     */
    public function expandCmsVersionTransferWithUser(CmsVersionTransfer $cmsVersionTransfer)
    {
        $userTransfer = $this->userFacade->getUserById($cmsVersionTransfer->getFkUser());
        $cmsVersionTransfer->setFirstName($userTransfer->getFirstName());
        $cmsVersionTransfer->setLastName($userTransfer->getLastName());

        return $cmsVersionTransfer;
    }

    /**
     * @param CmsVersionTransfer $cmsVersionTransfer
     * @param int $idUser
     *
     * @return SpyCmsVersion
     */
    protected function updateCmsVersionUserId(CmsVersionTransfer $cmsVersionTransfer, $idUser)
    {
        $cmsVersionEntity = $this->cmsQueryContainer
            ->queryCmsVersionById($cmsVersionTransfer->getIdCmsVersion())
            ->findOne();

        $cmsVersionEntity->setFkUser($idUser);
        $cmsVersionEntity->save();

        return $cmsVersionEntity;
    }

}
