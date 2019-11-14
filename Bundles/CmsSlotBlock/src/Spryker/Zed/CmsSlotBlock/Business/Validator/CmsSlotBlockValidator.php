<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Validator;

use Spryker\Shared\CmsSlotBlock\CmsSlotBlockConfig;
use Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToCmsSlotFacadeInterface;

class CmsSlotBlockValidator implements CmsSlotBlockValidatorInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToCmsSlotFacadeInterface
     */
    protected $cmsSlotFacade;

    /**
     * @param \Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToCmsSlotFacadeInterface $cmsSlotFacade
     */
    public function __construct(CmsSlotBlockToCmsSlotFacadeInterface $cmsSlotFacade)
    {
        $this->cmsSlotFacade = $cmsSlotFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return bool
     */
    public function getIsCmsSlotBlockListValid(array $cmsSlotBlockTransfers): bool
    {
        $cmsSlotValidationCache = [];

        foreach ($cmsSlotBlockTransfers as $cmsSlotBlockTransfer) {
            $idCmsSlot = $cmsSlotBlockTransfer->getIdSlot();

            if (isset($cmsSlotValidationCache[$idCmsSlot])) {
                continue;
            }

            $isCmsSlotValid = $this->validateCmsSlotById($idCmsSlot);

            if (!$isCmsSlotValid) {
                return false;
            }

            $cmsSlotValidationCache[$isCmsSlotValid] = $isCmsSlotValid;
        }

        return true;
    }

    /**
     * @param int $idCmsSlot
     *
     * @return bool
     */
    protected function validateCmsSlotById(int $idCmsSlot): bool
    {
        $cmsSlotTransfer = $this->cmsSlotFacade->findCmsSlotById($idCmsSlot);

        if (!$cmsSlotTransfer) {
            return false;
        }

        if ($cmsSlotTransfer->getContentProviderType() === CmsSlotBlockConfig::CMS_SLOT_CONTENT_PROVIDER_TYPE) {
            return false;
        }

        return true;
    }
}
