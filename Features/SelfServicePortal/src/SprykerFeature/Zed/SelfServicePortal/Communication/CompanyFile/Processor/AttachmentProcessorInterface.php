<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor;

use Generated\Shared\Transfer\FileAttachmentTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;

interface AttachmentProcessorInterface
{
    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processAssetForm(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse;

    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processBusinessUnitForm(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse;

    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processCompanyUserForm(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse;

    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processCompanyForm(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse;

    /**
     * @param array<string, mixed> $formData
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function processModelForm(array $formData, int $idFile, FileAttachmentTransfer $fileAttachmentTransfer): RedirectResponse;
}
