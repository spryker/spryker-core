<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Zed\CmsBlockGui\Communication\Form\Block\CmsBlockForm;
use Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface;
use Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToLocaleInterface;
use Spryker\Zed\CmsBlockGui\Dependency\QueryContainer\CmsBlockGuiToCmsBlockQueryContainerInterface;

class CmsBlockFormDataProvider
{

    /**
     * @var \Spryker\Zed\CmsBlockGui\Dependency\QueryContainer\CmsBlockGuiToCmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface
     */
    protected $cmsBlockFacade;

    /**
     * @var \Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToLocaleInterface
     */
    protected $localFacade;

    /**
     * @param \Spryker\Zed\CmsBlockGui\Dependency\QueryContainer\CmsBlockGuiToCmsBlockQueryContainerInterface $cmsBlockQueryContainer
     * @param \Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface $cmsBlockFacade
     * @param \Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToLocaleInterface $localFacade
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
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function getData($idCmsBlock = null)
    {
        if (!$idCmsBlock) {
            $cmsBlockTransfer = new CmsBlockTransfer();
        } else {
            $cmsBlockTransfer = $this->cmsBlockFacade->findCmsBlockById($idCmsBlock);
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
