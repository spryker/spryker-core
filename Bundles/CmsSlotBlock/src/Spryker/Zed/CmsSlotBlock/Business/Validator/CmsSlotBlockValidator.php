<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Validator;

use Exception;
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
    protected $validCmsSlotBuffer = [];

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

            if (!$this->isValidCmsSlot($idCmsSlot)) {
                $validationResponseTransfer->setIsSuccess(false);
                $validationResponseTransfer->addErrorMessage(
                    $this->createMessageTransfer($idCmsSlot)
                );
            }
        }

        return $validationResponseTransfer;
    }

    /**
     * @param int $idCmsSlot
     *
     * @return bool
     */
    protected function isValidCmsSlot(int $idCmsSlot): bool
    {
        if (isset($this->validCmsSlotBuffer[$idCmsSlot])) {
            return true;
        }

        try {
            $cmsSlotTransfer = $this->cmsSlotFacade->getCmsSlotById($idCmsSlot);
        } catch (Exception $cmsSlotException) {
            return false;
        }

        $isValidCmsSlot = $cmsSlotTransfer->getContentProviderType() === CmsSlotBlockConfig::CMS_SLOT_CONTENT_PROVIDER_TYPE;

        if ($isValidCmsSlot) {
            $this->validCmsSlotBuffer[$idCmsSlot] = true;
        }

        return $isValidCmsSlot;
    }

    /**
     * @param int $idCmsSlot
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMessageTransfer(int $idCmsSlot): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue(sprintf(
                'CMS slot with ID %s is not valid for saving in CMS slot block relation.',
                $idCmsSlot
            ));
    }
}
