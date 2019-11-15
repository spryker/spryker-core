<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Shared\CmsSlotBlock\CmsSlotBlockConfig;
use Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToCmsSlotFacadeInterface;

class CmsSlotBlockValidator implements CmsSlotBlockValidatorInterface
{
    /**
     * @var \Spryker\Zed\CmsSlotBlock\Dependency\Facade\CmsSlotBlockToCmsSlotFacadeInterface
     */
    protected $cmsSlotFacade;

    /**
     * @var bool[]
     */
    protected $cmsSlotValidationCache = [];

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
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function getIsCmsSlotBlockListValid(array $cmsSlotBlockTransfers): ValidationResponseTransfer
    {
        $validationResponseTransfer = new ValidationResponseTransfer();
        $validationResponseTransfer->setIsSuccess(true);

        foreach ($cmsSlotBlockTransfers as $cmsSlotBlockTransfer) {
            $idCmsSlot = $cmsSlotBlockTransfer->getIdSlot();

            if (isset($this->cmsSlotValidationCache[$idCmsSlot])) {
                continue;
            }

            $isCmsSlotValid = $this->validateCmsSlotById($idCmsSlot);

            if (!$isCmsSlotValid) {
                $messageTransfer = (new MessageTransfer())
                    ->setValue(sprintf(
                        'CMS slot with ID %s is not valid for saving in CMS slot block relation.',
                        $idCmsSlot
                    ));

                $validationResponseTransfer->setIsSuccess(false);
                $validationResponseTransfer->addErrorMessage($messageTransfer);
            }
        }

        return $validationResponseTransfer;
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

        $isCmsSlotValid = $cmsSlotTransfer->getContentProviderType() === CmsSlotBlockConfig::CMS_SLOT_CONTENT_PROVIDER_TYPE;
        $this->cmsSlotValidationCache[$idCmsSlot] = $isCmsSlotValid;

        return $isCmsSlotValid;
    }
}
