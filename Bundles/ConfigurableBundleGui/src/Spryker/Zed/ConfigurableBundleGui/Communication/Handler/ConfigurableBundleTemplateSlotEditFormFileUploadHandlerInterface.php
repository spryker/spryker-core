<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Handler;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer;
use Symfony\Component\Form\FormInterface;

interface ConfigurableBundleTemplateSlotEditFormFileUploadHandlerInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $configurableBundleTemplateSlotEditForm
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer
     */
    public function handleFileUploads(
        FormInterface $configurableBundleTemplateSlotEditForm,
        ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
    ): ConfigurableBundleTemplateSlotEditFormTransfer;
}
