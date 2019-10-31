<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Throwable;

class PageRemover implements PageRemoverInterface
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\CmsExtension\Dependency\Plugin\PreCmsPageRelationDeletePluginInterface[]
     */
    protected $preCmsPageRelationDeletePlugins;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToTouchFacadeInterface $touchFacade
     * @param \Spryker\Zed\CmsExtension\Dependency\Plugin\PreCmsPageRelationDeletePluginInterface[] $preCmsPageRelationDeletePlugins
     */
    public function __construct(
        CmsQueryContainerInterface $cmsQueryContainer,
        CmsToTouchFacadeInterface $touchFacade,
        array $preCmsPageRelationDeletePlugins
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->touchFacade = $touchFacade;
        $this->preCmsPageRelationDeletePlugins = $preCmsPageRelationDeletePlugins;
    }

    /**
     * @param int $idCmsPage
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function delete(int $idCmsPage): void
    {
        $this->cmsQueryContainer->getConnection()->beginTransaction();

        try {
            $cmsPageEntity = $this->findCmsPageEntity($idCmsPage);

            if ($cmsPageEntity) {
                $this->runPreCmsPageRelationDeletePlugins($cmsPageEntity);
                $this->deletePageWithRelations($cmsPageEntity);
                $this->touchDeletedPage($idCmsPage);
            }

            $this->cmsQueryContainer->getConnection()->commit();
        } catch (Throwable $exception) {
            $this->cmsQueryContainer->getConnection()->rollBack();
            throw $exception;
        }
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsPage|null
     */
    protected function findCmsPageEntity(int $idCmsPage): ?SpyCmsPage
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
    protected function runPreCmsPageRelationDeletePlugins(SpyCmsPage $cmsPageEntity): void
    {
        $cmsPageTransfer = $this->mapCmsPageEntityToTransfer($cmsPageEntity);

        foreach ($this->preCmsPageRelationDeletePlugins as $preCmsPageRelationDeletePlugin) {
            $preCmsPageRelationDeletePlugin->execute($cmsPageTransfer);
        }
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function mapCmsPageEntityToTransfer(SpyCmsPage $cmsPageEntity): CmsPageTransfer
    {
        $cmsPageTransfer = new CmsPageTransfer();
        $cmsPageTransfer->fromArray($cmsPageEntity->toArray(), true);

        return $cmsPageTransfer->setFkPage($cmsPageEntity->getIdCmsPage());
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return void
     */
    protected function deletePageWithRelations(SpyCmsPage $cmsPageEntity): void
    {
        $cmsPageEntity->getSpyUrls()->delete();

        $cmsPageEntity->getSpyCmsGlossaryKeyMappings()->delete();

        $cmsPageEntity->getSpyCmsPageStores()->delete();

        $cmsPageEntity->delete();
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    protected function touchDeletedPage(int $idCmsPage): void
    {
        $this->touchFacade->touchDeleted(CmsConstants::RESOURCE_TYPE_PAGE, $idCmsPage);
    }
}
