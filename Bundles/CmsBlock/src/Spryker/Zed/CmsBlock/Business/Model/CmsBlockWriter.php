<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlock;
use Spryker\Shared\CmsBlock\CmsBlockConstants;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockNotFoundException;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToGlossaryFacadeInterface;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchFacadeInterface;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;
use Spryker\Zed\CmsBlock\Communication\Plugin\CmsBlockUpdatePluginInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsBlockWriter implements CmsBlockWriterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var CmsBlockMapperInterface
     */
    protected $cmsBlockMapper;

    /**
     * @var CmsBlockGlossaryWriterInterface
     */
    protected $cmsBlockGlossaryWriter;

    /**
     * @var CmsBlockTemplateManagerInterface
     */
    protected $templateManager;

    /**
     * @var CmsBlockUpdatePluginInterface[]
     */
    protected $cmsBlockUpdatePlugins;

    /**
     * @param CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param CmsBlockMapperInterface $cmsBlockMapper
     * @param CmsBlockGlossaryWriterInterface $cmsBlockGlossaryWriter
     * @param CmsBlockToTouchFacadeInterface $touchFacade
     * @param CmsBlockTemplateManagerInterface $cmsBlockTemplateManager
     * @param CmsBlockUpdatePluginInterface[] $updatePlugins
     */
    public function __construct(
        CmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockMapperInterface $cmsBlockMapper,
        CmsBlockGlossaryWriterInterface $cmsBlockGlossaryWriter,
        CmsBlockToTouchFacadeInterface $touchFacade,
        CmsBlockTemplateManagerInterface $cmsBlockTemplateManager,
        array $updatePlugins
    ) {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->cmsBlockMapper = $cmsBlockMapper;
        $this->cmsBlockGlossaryWriter = $cmsBlockGlossaryWriter;
        $this->touchFacade = $touchFacade;
        $this->templateManager = $cmsBlockTemplateManager;
        $this->cmsBlockUpdatePlugins = $updatePlugins;
    }

    /**
     * @var CmsBlockToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function activateById($idCmsBlock)
    {
        $this->handleDatabaseTransaction(function () use ($idCmsBlock) {
            $spyCmsBlock = $this->getCmsBlockById($idCmsBlock);
            $spyCmsBlock->setIsActive(true);
            $spyCmsBlock->save();

            $this->touchFacade->touchActive(CmsBlockConstants::RESOURCE_TYPE_CMS_BLOCK, $spyCmsBlock->getIdCmsBlock());
        });
    }

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deactivateById($idCmsBlock)
    {
        $this->handleDatabaseTransaction(function () use ($idCmsBlock) {
            $spyCmsBlock = $this->getCmsBlockById($idCmsBlock);
            $spyCmsBlock->setIsActive(false);
            $spyCmsBlock->save();

            $this->touchFacade->touchDeleted(CmsBlockConstants::RESOURCE_TYPE_CMS_BLOCK, $spyCmsBlock->getIdCmsBlock());
        });
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @throws CmsBlockNotFoundException
     *
     * @return CmsBlockTransfer
     */
    public function updateCmsBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        $cmsBlockTransfer->requireIdCmsBlock();

        $spyCmsBlock = $this->getCmsBlockById($cmsBlockTransfer->getIdCmsBlock());
        $this->checkTemplateFileExists($cmsBlockTransfer->getFkTemplate());

        if ($spyCmsBlock === null) {
            throw new CmsBlockNotFoundException(
                sprintf(
                    'CMS Block with id "%d" was not found',
                    $cmsBlockTransfer->getIdCmsBlock()
                )
            );
        }

        $this->handleDatabaseTransaction(function () use ($cmsBlockTransfer, $spyCmsBlock) {
            $this->updateCmsBlockTransaction($cmsBlockTransfer, $spyCmsBlock);
            $this->updateCmsBlockPluginsTransaction($cmsBlockTransfer);
        });

        return $cmsBlockTransfer;
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return CmsBlockTransfer
     */
    public function createCmsBlock(CmsBlockTransfer $cmsBlockTransfer)
    {
        $cmsBlockTransfer->requireFkTemplate();

        $this->handleDatabaseTransaction(function () use ($cmsBlockTransfer) {
            return $this->createCmsBlockTransaction($cmsBlockTransfer);
        });

        return $cmsBlockTransfer;
    }

    /**
     * @param int $idCmsBlock
     *
     * @throws CmsBlockNotFoundException
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock
     */
    protected function getCmsBlockById($idCmsBlock)
    {
        $spyCmsBlock = $this->cmsBlockQueryContainer
            ->queryCmsBlockById($idCmsBlock)
            ->findOne();

        if (!$spyCmsBlock) {
            throw new CmsBlockNotFoundException();
        }

        return $spyCmsBlock;
    }

    /**
     * @param int $idCmsBlockTemplate
     *
     * @return void
     */
    protected function checkTemplateFileExists($idCmsBlockTemplate)
    {
        $templateTransfer = $this->templateManager
            ->getTemplateById($idCmsBlockTemplate);

        $this->templateManager
            ->checkTemplateFileExists($templateTransfer->getTemplatePath());
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     * @param SpyCmsBlock $spyCmsBlock
     *
     * @return void
     */
    protected function updateCmsBlockTransaction(CmsBlockTransfer $cmsBlockTransfer, SpyCmsBlock $spyCmsBlock)
    {
        if ($spyCmsBlock->getFkTemplate() !== $cmsBlockTransfer->getFkTemplate()) {
            $this->cmsBlockGlossaryWriter->deleteByCmsBlockId($spyCmsBlock->getIdCmsBlock());
        }

        $spyCmsBlock = $this->cmsBlockMapper->mapCmsBlockTransferToEntity($cmsBlockTransfer, $spyCmsBlock);
        $spyCmsBlock->save();

        if ($spyCmsBlock->getIsActive()) {
            $this->touchFacade->touchActive(CmsBlockConstants::RESOURCE_TYPE_CMS_BLOCK, $spyCmsBlock->getIdCmsBlock());
        }

    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     *
     * @return void
     */
    protected function updateCmsBlockPluginsTransaction(CmsBlockTransfer $cmsBlockTransfer)
    {
        foreach ($this->cmsBlockUpdatePlugins as $updatePlugin) {
            $updatePlugin->handleUpdate($cmsBlockTransfer);
        }
    }

    /**
     * @param CmsBlockTransfer $cmsBlockTransfer
     */
    protected function createCmsBlockTransaction(CmsBlockTransfer $cmsBlockTransfer)
    {
        $spyCmsBlock = new SpyCmsBlock();
        $spyCmsBlock = $this->cmsBlockMapper->mapCmsBlockTransferToEntity($cmsBlockTransfer, $spyCmsBlock);
        $spyCmsBlock->save();

        if ($spyCmsBlock->getIsActive()) {
            $this->touchFacade->touchActive(CmsBlockConstants::RESOURCE_TYPE_CMS_BLOCK, $spyCmsBlock->getIdCmsBlock());
        }

        $cmsBlockTransfer->setIdCmsBlock($spyCmsBlock->getIdCmsBlock());
    }

}