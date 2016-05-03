<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Form\DataProvider;

use Spryker\Zed\Cms\Communication\Form\CmsBlockForm;
use Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface;

class CmsBlockFormDataProvider
{

    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct(CmsQueryContainerInterface $cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param int|null $idCmsBlock
     *
     * @return array
     */
    public function getData($idCmsBlock = null)
    {
        $formData = [];

        if ($idCmsBlock) {
            $pageUrlTemplate = $this->cmsQueryContainer
                ->queryPageWithTemplatesAndBlocksById($idCmsBlock)
                ->findOne();

            return [
                CmsBlockForm::FIELD_ID_CMS_BLOCK => $pageUrlTemplate->getIdCmsBlock(),
                CmsBlockForm::FIELD_FK_PAGE => $pageUrlTemplate->getFkPage(),
                CmsBlockForm::FIELD_FK_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                CmsBlockForm::FIELD_NAME => $pageUrlTemplate->getName(),
                CmsBlockForm::FIELD_TYPE => $pageUrlTemplate->getType(),
                CmsBlockForm::FIELD_SELECT_VALUE => $pageUrlTemplate->getCategoryName(),
                CmsBlockForm::FIELD_VALUE => $pageUrlTemplate->getValue(),
                CmsBlockForm::FIELD_CURRENT_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                CmsBlockForm::FIELD_IS_ACTIVE => (bool)$pageUrlTemplate->getIsActive(),
            ];
        }

        return $formData;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CmsBlockForm::OPTION_TEMPLATE_CHOICES => $this->getTemplateList(),
        ];
    }

    /**
     * @return array
     */
    protected function getTemplateList()
    {
        $templates = $this->cmsQueryContainer->queryTemplates()->find();

        $result = [];

        /** @var \Orm\Zed\Cms\Persistence\SpyCmsTemplate $template */
        foreach ($templates->getData() as $template) {
            $result[$template->getIdCmsTemplate()] = $template->getTemplateName();
        }

        return $result;
    }

}
