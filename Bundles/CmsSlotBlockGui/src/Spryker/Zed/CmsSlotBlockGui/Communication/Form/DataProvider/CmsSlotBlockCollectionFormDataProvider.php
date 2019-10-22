<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotFacadeInterface;

class CmsSlotBlockCollectionFormDataProvider implements CmsSlotBlockCollectionFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotFacadeInterface
     */
    protected $cmsSlotFacade;

    /**
     * @var \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface
     */
    protected $cmsSlotBlockFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotFacadeInterface $cmsSlotFacade
     * @param \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface $cmsSlotBlockFacade
     */
    public function __construct(
        CmsSlotBlockGuiToCmsSlotFacadeInterface $cmsSlotFacade,
        CmsSlotBlockGuiToCmsSlotBlockFacadeInterface $cmsSlotBlockFacade
    ) {
        $this->cmsSlotFacade = $cmsSlotFacade;
        $this->cmsSlotBlockFacade = $cmsSlotBlockFacade;
    }

    /**
     * @param int $idCmsSlotTemplate
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer
     */
    public function getData(int $idCmsSlotTemplate, int $idCmsSlot): CmsSlotBlockCollectionTransfer
    {
        return $this->cmsSlotBlockFacade->getCmsSlotBlockCollection($idCmsSlotTemplate, $idCmsSlot);
    }

    /**
     * @param int $idCmsSlotTemplate
     *
     * @return array
     */
    public function getOptions(int $idCmsSlotTemplate): array
    {
        $cmsSlotTemplateTransfer = $this->cmsSlotFacade->getCmsSlotTemplateById($idCmsSlotTemplate);
        $templateConditions = $this->cmsSlotBlockFacade->getTemplateConditionsByPath($cmsSlotTemplateTransfer->getPath());

        return [
            'conditions' => $templateConditions,
        ];
    }
}
