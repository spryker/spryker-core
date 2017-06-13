<?php


namespace Spryker\Zed\CmsGui\Communication\Form\DataProvider;


use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsBlockGlossaryForm;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsBlockInterface;

class CmsBlockGlossaryFormDataProvider
{

    /**
     * @var CmsGuiToCmsBlockInterface
     */
    protected $cmsBlockFacade;

    /**
     * @param CmsGuiToCmsBlockInterface $cmsBlockFacade
     */
    public function __construct(CmsGuiToCmsBlockInterface $cmsBlockFacade)
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