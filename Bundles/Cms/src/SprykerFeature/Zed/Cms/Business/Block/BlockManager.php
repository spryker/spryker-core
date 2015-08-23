<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Business\Block;

use Generated\Shared\Transfer\CmsBlockTransfer;
use SprykerFeature\Shared\Cms\CmsConfig;
use SprykerFeature\Zed\Cms\Dependency\Facade\CmsToTouchInterface;
use SprykerFeature\Zed\Cms\Persistence\CmsQueryContainerInterface;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsBlock;

class BlockManager implements BlockManagerInterface
{

    /**
     * @var CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var CmsToTouchInterface
     */
    protected $touchFacade;

    /**
     * @param CmsQueryContainerInterface $cmsQueryContainer
     * @param CmsToTouchInterface $touchFacade
     */
    public function __construct(CmsQueryContainerInterface $cmsQueryContainer, CmsToTouchInterface $touchFacade)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param CmsBlockTransfer $cmsBlock
     *
     * @return CmsBlockTransfer
     */
    public function saveBlock(CmsBlockTransfer $cmsBlock)
    {
        $this->checkPageExists($cmsBlock->getIdCmsPage());

        if (is_null($this->getCmsBlockByIdPage($cmsBlock->getIdCmsPage()))) {
            $block = $this->createBlock($cmsBlock);
        } else {
            $block = $this->updateBlock($cmsBlock);
        }

        return $this->convertBlockEntityToTransfer($block);
    }

    /**
     * @param CmsBlockTransfer $cmsBlock
     *
     * @return CmsBlockTransfer
     */
    public function saveBlockAndTouch(CmsBlockTransfer $cmsBlock)
    {
        $blockTransfer = $this->saveBlock($cmsBlock);
        $this->touchBlockActive($blockTransfer);

        return $blockTransfer;
    }

    /**
     * @param SpyCmsBlock $block
     *
     * @return CmsBlockTransfer
     */
    public function convertBlockEntityToTransfer(SpyCmsBlock $block)
    {
        $blockTransfer = new CmsBlockTransfer();
        $blockTransfer->fromArray($block->toArray(), true);

        return $blockTransfer;
    }

    /**
     * @param CmsBlockTransfer $cmsBlock
     */
    public function touchBlockActive(CmsBlockTransfer $cmsBlock)
    {
        $this->touchFacade->touchActive(CmsConfig::RESOURCE_TYPE_BLOCK, $cmsBlock->getIdCmsPage());
    }

    /**
     * @param CmsBlockTransfer $cmsBlock
     *
     * @return SpyCmsBlock
     */
    protected function createBlock(CmsBlockTransfer $cmsBlock)
    {
        $blockEntity = new SpyCmsBlock();

        $blockEntity->fromArray($cmsBlock->toArray());
        $blockEntity->save();

        return $blockEntity;
    }

    /**
     * @param CmsBlockTransfer $cmsBlock
     *
     * @return SpyCmsBlock
     */
    protected function updateBlock(CmsBlockTransfer $cmsBlock)
    {
        $blockEntity = $this->getCmsBlockByIdPage($cmsBlock->getIdCmsPage());
        $blockEntity->fromArray($cmsBlock->toArray());

        if (!$blockEntity->isModified()) {
            return $blockEntity;
        }

        $blockEntity->save();

        return $blockEntity;
    }

    /**
     * @param int $idPage
     *
     * @throws MissingPageException
     */
    protected function checkPageExists($idPage)
    {
        if (!$this->cmsQueryContainer->queryPageById($idPage)
                ->count() > 0
        ) {
            throw new MissingPageException(sprintf('Tried to refer to a missing page with id %s', $idPage));
        }
    }

    /**
     * @param int $idCmsPage
     *
     * @return SpyCmsBlock
     */
    protected function getCmsBlockByIdPage($idCmsPage)
    {
        $blockEntity = $this->cmsQueryContainer->queryBlockByIdPage($idCmsPage)
            ->findOne()
        ;

        return $blockEntity;
    }
}
