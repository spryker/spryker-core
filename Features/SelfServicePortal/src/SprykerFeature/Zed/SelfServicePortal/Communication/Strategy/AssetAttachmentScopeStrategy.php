<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Strategy;

use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\FormDataNormalizerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class AssetAttachmentScopeStrategy implements AttachmentScopeStrategyInterface
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_FILE_ATTACHMENT = 'fileAttachment';

    /**
     * @var string
     */
    protected const SCOPE_TYPE_ASSET = 'asset';

    public function __construct(protected FormDataNormalizerInterface $formDataNormalizer)
    {
    }

    public function getScopeType(): string
    {
        return static::SCOPE_TYPE_ASSET;
    }

    public function canProcess(FormInterface $form): bool
    {
        return $form->isValid();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>|null
     */
    public function getFormData(Request $request): ?array
    {
        $fileAttachmentData = $request->get(static::REQUEST_PARAM_FILE_ATTACHMENT);

        if ($fileAttachmentData === null) {
            return null;
        }

        return $this->formDataNormalizer->cleanFormData($fileAttachmentData ?: []);
    }
}
