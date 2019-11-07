<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsSlotBlockCollectionTransfer;
use Generated\Shared\Transfer\CmsSlotBlockCriteriaTransfer;
use Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface;

class CmsSlotBlockCollectionFormDataProvider implements CmsSlotBlockCollectionFormDataProviderInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface
     */
    protected $cmsSlotBlockFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlockGui\Dependency\Facade\CmsSlotBlockGuiToCmsSlotBlockFacadeInterface $cmsSlotBlockFacade
     */
    public function __construct(CmsSlotBlockGuiToCmsSlotBlockFacadeInterface $cmsSlotBlockFacade)
    {
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
        $cmsSlotBlockCriteriaTransfer = (new CmsSlotBlockCriteriaTransfer())
            ->setIdCmsSlotTemplate($idCmsSlotTemplate)
            ->setIdCmsSlot($idCmsSlot);

        return $this->cmsSlotBlockFacade->getCmsSlotBlockCollection($cmsSlotBlockCriteriaTransfer);
    }
}
