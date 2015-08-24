<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Business\Block;

use Generated\Shared\Cms\CmsBlockInterface;
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
     * @param CmsBlockInterface $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function saveBlock(CmsBlockInterface $cmsBlockTransfer)
    {
        $this->checkPageExists($cmsBlockTransfer->getIdCmsPage());

        if (null === $this->getCmsBlockByIdPage($cmsBlockTransfer->getIdCmsPage())) {
            $block = $this->createBlock($cmsBlockTransfer);
        } else {
            $block = $this->updateBlock($cmsBlockTransfer);
        }

        return $this->convertBlockEntityToTransfer($block);
    }

    /**
     * @param CmsBlockInterface $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function saveBlockAndTouch(CmsBlockInterface $cmsBlockTransfer)
    {
        $blockTransfer = $this->saveBlock($cmsBlockTransfer);
        $this->touchBlockActive($blockTransfer);

        return $blockTransfer;
    }

    /**
     * @param SpyCmsBlock $blockEntity
     *
     * @return CmsBlockTransfer
     */
    public function convertBlockEntityToTransfer(SpyCmsBlock $blockEntity)
    {
        $blockTransfer = new CmsBlockTransfer();
        $blockTransfer->fromArray($blockEntity->toArray(), true);

        return $blockTransfer;
    }

    /**
     * @param CmsBlockInterface $cmsBlockTransfer
     */
    public function touchBlockActive(CmsBlockInterface $cmsBlockTransfer)
    {
        $this->touchFacade->touchActive(CmsConfig::RESOURCE_TYPE_BLOCK, $cmsBlockTransfer->getIdCmsPage());
    }

    /**
     * @param CmsBlockInterface $cmsBlockTransfer
     *
     * @return SpyCmsBlock
     */
    protected function createBlock(CmsBlockInterface $cmsBlockTransfer)
    {
        $blockEntity = new SpyCmsBlock();

        $blockEntity->fromArray($cmsBlockTransfer->toArray());
        $blockEntity->save();

        return $blockEntity;
    }

    /**
     * @param CmsBlockInterface $cmsBlockTransfer
     *
     * @return SpyCmsBlock
     */
    protected function updateBlock(CmsBlockInterface $cmsBlockTransfer)
    {
        $blockEntity = $this->getCmsBlockByIdPage($cmsBlockTransfer->getIdCmsPage());
        $blockEntity->fromArray($cmsBlockTransfer->toArray());

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
