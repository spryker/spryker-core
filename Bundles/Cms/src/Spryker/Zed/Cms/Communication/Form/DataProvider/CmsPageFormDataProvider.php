<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Cms\Communication\Form\DataProvider;

use Spryker\Zed\Cms\Communication\Form\CmsPageForm;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsPageFormDataProvider
{

    const ADD = 'add';
    const UPDATE = 'update';

    const PAGE = 'Page';

    const FIELD_URL = 'url';
    const FIELD_ID_CMS_PAGE = 'idCmsPage';
    const FIELD_FK_TEMPLATE = 'fkTemplate';
    const FIELD_CURRENT_TEMPLATE = 'cur_temp';
    const FIELD_IS_ACTIVE = 'is_active';

    /**
     * @var CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param CmsQueryContainer $cmsQueryContainer
     */
    public function __construct(CmsQueryContainer $cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param int|null $idPage
     *
     * @return array
     */
    public function getData($idPage = null)
    {
        if ($idPage === null) {
            return [];
        }

        $pageUrlTemplate = $this
            ->cmsQueryContainer
            ->queryPageWithTemplatesAndUrlByIdPage($idPage)
            ->findOne();

        return [
            CmsPageForm::FIELD_ID_CMS_PAGE => $pageUrlTemplate->getIdCmsPage(),
            CmsPageForm::FIELD_FK_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
            CmsPageForm::FIELD_URL => $pageUrlTemplate->getUrl(),
            CmsPageForm::FIELD_CURRENT_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
            CmsPageForm::FIELD_IS_ACTIVE => $pageUrlTemplate->getIsActive(),
            CmsPageForm::FIELD_ID_URL => $pageUrlTemplate->getIdUrl(),
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CmsPageForm::OPTION_TEMPLATE_CHOICES => $this->getTemplateList(),
        ];
    }

    /**
     * @return array
     */
    protected function getTemplateList()
    {
        $templates = $this->cmsQueryContainer->queryTemplates()->find();

        $result = [];
        foreach ($templates->getData() as $template) {
            $result[$template->getIdCmsTemplate()] = $template->getTemplateName();
        }

        return $result;
    }

}
