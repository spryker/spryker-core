<?php


namespace Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider;


use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface;
use Spryker\Zed\CmsBlockGui\Communication\Form\Glossary\CmsBlockGlossaryForm;

class CmsBlockGlossaryFormDataProvider
{

    /**
     * @var CmsBlockGuiToCmsBlockInterface
     */
    protected $cmsBlockFacade;

    /**
     * @param CmsBlockGuiToCmsBlockInterface $cmsBlockFacade
     */
    public function __construct(CmsBlockGuiToCmsBlockInterface $cmsBlockFacade)
    {
        $this->cmsBlockFacade = $cmsBlockFacade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CmsBlockGlossaryTransfer::class,
            CmsBlockGlossaryForm::OPTION_DATA_CLASS_PLACEHOLDERS => CmsBlockGlossaryPlaceholderTransfer::class,
        ];
    }

    /**
     * @param int $idCmsBlock
     *
     * @return CmsBlockGlossaryTransfer
     */
    public function getData($idCmsBlock)
    {
        return $this->cmsBlockFacade->findGlossaryPlaceholders($idCmsBlock);
    }
}