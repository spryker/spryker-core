<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Exception;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class PageRemover implements PageRemoverInterface
{

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface $touchFacade
     */
    public function __construct(CmsQueryContainerInterface $cmsQueryContainer, CmsToTouchInterface $touchFacade)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Exception
     *
     * @return void
     */
    public function delete($idCmsPage)
    {
        $this->cmsQueryContainer->getConnection()->beginTransaction();

        try {
            $cmsPageEntity = $this->findCmsPageEntity($idCmsPage);

            if ($cmsPageEntity) {
                $this->deletePageWithRelations($cmsPageEntity);
                $this->touchDeletedPage($idCmsPage);
            }

            $this->cmsQueryContainer->getConnection()->commit();
        } catch (Exception $e) {
            $this->cmsQueryContainer->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage|null
     */
    protected function findCmsPageEntity($idCmsPage)
    {
        $cmsPageEntity = $this
            ->cmsQueryContainer
            ->queryPageById($idCmsPage)
            ->findOne();

        return $cmsPageEntity;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return void
     */
    protected function deletePageWithRelations(SpyCmsPage $cmsPageEntity)
    {
        $cmsPageEntity->getSpyUrls()->delete();

        $cmsPageEntity->getSpyCmsGlossaryKeyMappings()->delete();

        $cmsPageEntity->delete();
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    protected function touchDeletedPage($idCmsPage)
    {
        $this->touchFacade->touchDeleted(CmsConstants::RESOURCE_TYPE_PAGE, $idCmsPage);
    }

}
