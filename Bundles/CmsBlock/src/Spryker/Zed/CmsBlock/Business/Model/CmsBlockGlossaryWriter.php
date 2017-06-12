<?php


namespace Spryker\Zed\CmsBlock\Business\Model;


use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockGlossaryKeyMappingTableMap;
use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToGlossaryFacadeInterface;
use Spryker\Zed\CmsBlock\Persistence\CmsBlockQueryContainerInterface;
use Spryker\Zed\Glossary\Business\GlossaryFacadeInterface;
use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

class CmsBlockGlossaryWriter implements CmsBlockGlossaryWriterInterface
{

    /**
     * @var CmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var GlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param CmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param CmsBlockToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        CmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return void
     */
    public function deleteByCmsBlockId($idCmsBlock)
    {
        $glossaryKeys = $this->cmsBlockQueryContainer
            ->queryCmsBlockGlossaryKeyMappingByIdCmsBlock($idCmsBlock)
            ->select([SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_GLOSSARY_KEY])
            ->find()
            ->getColumnValues(SpyCmsBlockGlossaryKeyMappingTableMap::COL_FK_GLOSSARY_KEY);

        if (empty($glossaryKeys)) {
            return;
        }

        $this->glossaryFacade->deleteTranslationsByFkKeys($glossaryKeys);
        $this->glossaryFacade->deleteKeys($glossaryKeys);
    }

}