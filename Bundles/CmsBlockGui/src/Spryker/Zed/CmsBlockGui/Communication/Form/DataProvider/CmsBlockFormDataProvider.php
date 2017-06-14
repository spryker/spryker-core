<?php


namespace Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockGui\Communication\Form\Block\CmsBlockForm;
use Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface;
use Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToLocaleInterface;
use Spryker\Zed\CmsBlockGui\Dependency\QueryContainer\CmsBlockGuiToCmsBlockQueryContainerInterface;

class CmsBlockFormDataProvider
{

    /**
     * @var CmsBlockGuiToCmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var CmsBlockGuiToCmsBlockInterface
     */
    protected $cmsBlockFacade;

    /**
     * @var \Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToLocaleInterface
     */
    protected $localFacade;

    /**
     * @param CmsBlockGuiToCmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param CmsBlockGuiToCmsBlockInterface $cmsBlockFacade
     * @param CmsBlockGuiToLocaleInterface $localFacade
     */
    public function __construct(
        CmsBlockGuiToCmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsBlockGuiToCmsBlockInterface $cmsBlockFacade,
        CmsBlockGuiToLocaleInterface $localFacade
    ) {
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
        $this->cmsBlockFacade = $cmsBlockFacade;
        $this->localFacade = $localFacade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CmsBlockTransfer::class,
            CmsBlockForm::OPTION_TEMPLATE_CHOICES => $this->getTemplateList(),
        ];
    }

    /**
     * @param int|null $idCmsBlock
     *
     * @return CmsBlockTransfer
     */
    public function getData($idCmsBlock = null)
    {
        if (!$idCmsBlock) {
            $cmsBlockTransfer = new CmsBlockTransfer();
        } else {
            $cmsBlockTransfer = $this->cmsBlockFacade->findCmsBlockId($idCmsBlock);
        }

        return $cmsBlockTransfer;
    }

    /**
     * @return array
     */
    protected function getTemplateList()
    {
        $templateCollection = $this->cmsBlockQueryContainer
            ->queryTemplates()
            ->find();

        $templateList = [];

        /** @var \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockTemplate $template */
        foreach ($templateCollection->getData() as $template) {
            $templateList[$template->getIdCmsBlockTemplate()] = $template->getTemplateName();
        }

        return $templateList;
    }
}