<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Block;

use Orm\Zed\Cms\Persistence\SpyCmsBlock;
use Spryker\Shared\Cms\CmsConstants;
use Spryker\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class BlockRemover implements BlockRemoverInterface
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
     * @param int $idCmsBlock
     *
     * @throws \Exception
     *
     * @return void
     */
    public function delete($idCmsBlock)
    {
        $this->cmsQueryContainer->getConnection()->beginTransaction();

        try {
            $cmsBlockEntity = $this->findCmsBlockEntity($idCmsBlock);

            if ($cmsBlockEntity) {
                $this->deleteBlockWithRelations($cmsBlockEntity);
                $this->touchDeletedBlock($idCmsBlock);
            }

            $this->cmsQueryContainer->getConnection()->commit();
        } catch (\Exception $e) {
            $this->cmsQueryContainer->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\Cms\Persistence\SpyCmsBlock|null
     */
    protected function findCmsBlockEntity($idCmsBlock)
    {
        $cmsBlockEntity = $this
            ->cmsQueryContainer
            ->queryBlockById($idCmsBlock)
            ->findOne();

        return $cmsBlockEntity;
    }

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsBlock $cmsBlockEntity
     *
     * @return void
     */
    protected function deleteBlockWithRelations(SpyCmsBlock $cmsBlockEntity)
    {
        $spyCmsPage = $cmsBlockEntity->getSpyCmsPage();

        $spyCmsPage->getSpyCmsGlossaryKeyMappings()->delete();

        $spyCmsPage->delete();

        $cmsBlockEntity->delete();
    }

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    protected function touchDeletedBlock($idCmsBlock)
    {
        $this->touchFacade->touchDeleted(CmsConstants::RESOURCE_TYPE_BLOCK, $idCmsBlock);
    }

}
