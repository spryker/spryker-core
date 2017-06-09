<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Spryker\Shared\CmsBlock\CmsBlockConstants;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockNotFoundException;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToTouchFacadeInterface;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CmsBlockWriter implements CmsBlockWriterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var CmsBlockToTouchFacadeInterface
     */
    protected $touchFacade;

    public function __construct(CmsBlockQueryContainerInterface $cmsBlockQueryContainer, CmsBlockToTouchFacadeInterface $touchFacade)
    {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->touchFacade = $touchFacade;
    }

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

            $this->touchFacade->touchActive(CmsBlockConstants::RESOURCE_TYPE_BLOCK, $spyCmsBlock->getIdCmsBlock());
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

            $this->touchFacade->touchDeleted(CmsBlockConstants::RESOURCE_TYPE_BLOCK, $spyCmsBlock->getIdCmsBlock());
        });
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
}