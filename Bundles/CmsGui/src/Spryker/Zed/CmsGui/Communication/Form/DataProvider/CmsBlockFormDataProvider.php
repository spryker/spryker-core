<?php


namespace Spryker\Zed\CmsGui\Communication\Form\DataProvider;


use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;
use Spryker\Zed\CmsGui\Communication\Form\Block\CmsBlockForm;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;

class CmsBlockFormDataProvider
{

    /**
     * @var CmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToLocaleInterface
     */
    protected $localFacade;

    /**
     * @param CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     * @param CmsGuiToCmsInterface $cmsFacade
     * @param CmsGuiToLocaleInterface $localFacade
     */
    public function __construct(
        CmsGuiToCmsQueryContainerInterface $cmsQueryContainer,
        CmsGuiToCmsInterface $cmsFacade,
        CmsGuiToLocaleInterface $localFacade
    ) {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsFacade = $cmsFacade;
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
     * @param int|null $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function getData($idCmsBlock = null)
    {
        if (!$idCmsBlock) {
            $cmsBlockTransfer = new CmsBlockTransfer();
        } else {
            $cmsBlockTransfer = $this->cmsFacade->findCmsPageById($idCmsBlock);
        }

        $cmsPageTransfer->setIsSearchable(true);

        return $cmsPageTransfer;
    }

    /**
     * @return array
     */
    protected function getTemplateList()
    {
        $templateCollection = $this->cmsQueryContainer->queryTemplates()->find();

        $templateList = [];

        /** @var \Orm\Zed\Cms\Persistence\SpyCmsTemplate $template */
        foreach ($templateCollection->getData() as $template) {
            $templateList[$template->getIdCmsTemplate()] = $template->getTemplateName();
        }

        return $templateList;
    }
}