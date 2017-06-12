<?php


namespace Spryker\Zed\CmsGui\Communication\Form\DataProvider;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\CmsGui\Communication\Form\Block\CmsBlockForm;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsBlockInterface;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsBlockQueryContainerInterface;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;

class CmsBlockFormDataProvider
{

    /**
     * @var CmsGuiToCmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var CmsGuiToCmsBlockInterface
     */
    protected $cmsBlockFacade;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface
     */
    protected $localFacade;

    /**
     * @param CmsGuiToCmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param CmsGuiToCmsBlockInterface $cmsBlockFacade
     * @param CmsGuiToLocaleInterface $localFacade
     */
    public function __construct(
        CmsGuiToCmsBlockQueryContainerInterface $cmsBlockQueryContainer,
        CmsGuiToCmsBlockInterface $cmsBlockFacade,
        CmsGuiToLocaleInterface $localFacade
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