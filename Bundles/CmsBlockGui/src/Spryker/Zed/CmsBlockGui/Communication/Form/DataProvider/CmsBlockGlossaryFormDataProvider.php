<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsBlockGlossaryPlaceholderTransfer;
use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Spryker\Zed\CmsBlockGui\Communication\Form\Glossary\CmsBlockGlossaryForm;
use Spryker\Zed\CmsBlockGui\Communication\Updater\CmsBlockGlossaryUpdaterInterface;
use Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface;

class CmsBlockGlossaryFormDataProvider
{
    /**
     * @var \Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface
     */
    protected $cmsBlockFacade;

    /**
     * @var \Spryker\Zed\CmsBlockGui\Communication\Updater\CmsBlockGlossaryUpdaterInterface
     */
    protected $cmsGlossaryUpdater;

    /**
     * @param \Spryker\Zed\CmsBlockGui\Dependency\Facade\CmsBlockGuiToCmsBlockInterface $cmsBlockFacade
     * @param \Spryker\Zed\CmsBlockGui\Communication\Updater\CmsBlockGlossaryUpdaterInterface $cmsGlossaryUpdater
     */
    public function __construct(CmsBlockGuiToCmsBlockInterface $cmsBlockFacade, CmsBlockGlossaryUpdaterInterface $cmsGlossaryUpdater)
    {
        $this->cmsBlockFacade = $cmsBlockFacade;
        $this->cmsGlossaryUpdater = $cmsGlossaryUpdater;
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
        $cmsBlockGlossaryTransfer = $this->cmsGlossaryUpdater->updateAfterFind($cmsBlockGlossaryTransfer);

        return $cmsBlockGlossaryTransfer;
    }
}
