<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\Business\Exception\MissingPageException;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsPageActivator implements CmsPageActivatorInterface
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
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface $touchFacade
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface $urlFacade
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsToTouchInterface $touchFacade,
        CmsToUrlInterface $urlFacade
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     */
    public function activate($idCmsPage)
    {
        $cmsPageEntity = $this->getCmsPageEntity($idCmsPage);

        $this->cmsQueryContainer->getConnection()->beginTransaction();

        $cmsPageEntity->setIsActive(true);
        $cmsPageEntity->save();

        $this->activatePageUrls($cmsPageEntity);

        $this->touchFacade->touchActive(CmsConstants::RESOURCE_TYPE_PAGE, $cmsPageEntity->getIdCmsPage(), true);

        $this->cmsQueryContainer->getConnection()->commit();

    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     */
    public function deactivate($idCmsPage)
    {
        $cmsPageEntity = $this->getCmsPageEntity($idCmsPage);

        $this->cmsQueryContainer->getConnection()->beginTransaction();

        $cmsPageEntity->setIsActive(false);
        $cmsPageEntity->save();

        $this->touchFacade->touchInactive(CmsConstants::RESOURCE_TYPE_PAGE, $cmsPageEntity->getIdCmsPage());

        $this->cmsQueryContainer->getConnection()->commit();

    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\Cms\Business\Exception\MissingPageException
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage
     */
    protected function getCmsPageEntity($idCmsPage)
    {
        $cmsPageEntity = $this->cmsQueryContainer
            ->queryPageById($idCmsPage)
            ->findOne();

        if ($cmsPageEntity === null) {
            throw new MissingPageException(
                sprintf(
                    'CMS page with id "%d" not found.',
                    $idCmsPage
                )
            );
        }
        return $cmsPageEntity;
    }

    /**
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return void
     */
    protected function activatePageUrls(SpyCmsPage $cmsPageEntity)
    {
        foreach ($cmsPageEntity->getSpyUrls() as $urlEntity) {
            $this->urlFacade->touchUrlActive($urlEntity->getIdUrl());
        }
    }
}
