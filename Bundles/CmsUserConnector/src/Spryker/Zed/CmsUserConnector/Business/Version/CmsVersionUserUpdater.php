<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsUserConnector\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;
use Spryker\Zed\CmsUserConnector\Dependency\Facade\CmsUserConnectorToUserInterface;
use Spryker\Zed\CmsUserConnector\Dependency\QueryContainer\CmsUserConnectorToCmsQueryContainerInterface;

class CmsVersionUserUpdater implements CmsVersionUserUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CmsUserConnector\Dependency\Facade\CmsUserConnectorToUserInterface
     */
    protected $userFacade;

    /**
     * @var \Spryker\Zed\CmsUserConnector\Dependency\QueryContainer\CmsUserConnectorToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param \Spryker\Zed\CmsUserConnector\Dependency\Facade\CmsUserConnectorToUserInterface $userFacade
     * @param \Spryker\Zed\CmsUserConnector\Dependency\QueryContainer\CmsUserConnectorToCmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(CmsUserConnectorToUserInterface $userFacade, CmsUserConnectorToCmsQueryContainerInterface $cmsQueryContainer)
    {
        $this->userFacade = $userFacade;
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function updateCmsVersionUser(CmsVersionTransfer $cmsVersionTransfer)
    {
        if (!$this->userFacade->hasCurrentUser()) {
            return $cmsVersionTransfer;
        }

        $idUser = $this->userFacade->getCurrentUser()->getIdUser();
        $cmsVersionEntity = $this->updateCmsVersionUserId($cmsVersionTransfer, $idUser);
        $cmsVersionTransfer->fromArray($cmsVersionEntity->toArray(), true);

        return $cmsVersionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     * @param int $idUser
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsVersion
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
