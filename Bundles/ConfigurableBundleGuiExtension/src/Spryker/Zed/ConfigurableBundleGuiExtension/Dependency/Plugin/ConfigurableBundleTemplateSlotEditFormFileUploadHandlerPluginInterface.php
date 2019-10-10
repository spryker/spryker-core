<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ConfigurableBundleTemplateSlotEditFormFileUploadHandlerPluginInterface
{
    /**
     * Specification:
     * - Handles file upload for ConfigurableBundleTemplateSlotEditForm.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotEditFormTransfer
     */
    public function hanldeFileUpload(UploadedFile $uploadedFile, ConfigurableBundleTemplateSlotEditFormTransfer $configurableBundleTemplateSlotEditFormTransfer): ConfigurableBundleTemplateSlotEditFormTransfer;

    /**
     * Specification:
     * - Returns dot-separated path to form field.
     *
     * @api
     *
     * @return string
     */
    public function getFieldPath(): string;
}
