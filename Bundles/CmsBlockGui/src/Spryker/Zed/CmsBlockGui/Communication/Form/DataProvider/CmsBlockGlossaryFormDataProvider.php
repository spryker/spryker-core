<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Zed\CmsBlockGui\Communication\Expander\CmsBlockGlossaryExpanderInterface;
use Spryker\Zed\CmsBlockGui\Communication\Form\Glossary\CmsBlockGlossaryForm;
use Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface;

class CmsBlockGlossaryFormDataProvider
{
    /**
     * @var \Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface
     */
    protected $cmsBlockFacade;

    /**
     * @var \Spryker\Zed\CmsBlockGui\Communication\Expander\CmsBlockGlossaryExpanderInterface
     */
    protected $cmsGlossaryExpander;

    /**
     * @param \Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface $cmsBlockFacade
     * @param \Spryker\Zed\CmsBlockGui\Communication\Expander\CmsBlockGlossaryExpanderInterface $cmsGlossaryExpander
     */
    public function __construct(CmsBlockGuiToCmsBlockInterface $cmsBlockFacade, CmsBlockGlossaryExpanderInterface $cmsGlossaryExpander)
    {
        $this->cmsBlockFacade = $cmsBlockFacade;
        $this->cmsGlossaryExpander = $cmsGlossaryExpander;
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
     * @return \Generated\Shared\Transfer\CmsBlockGlossaryTransfer
     */
    public function getData($idCmsBlock)
    {
        $cmsBlockGlossaryTransfer = $this->cmsBlockFacade->findGlossary($idCmsBlock);
        $cmsBlockGlossaryTransfer = $this->cmsGlossaryExpander->executeAfterFindPlugins($cmsBlockGlossaryTransfer);

        return $cmsBlockGlossaryTransfer;
    }
}
