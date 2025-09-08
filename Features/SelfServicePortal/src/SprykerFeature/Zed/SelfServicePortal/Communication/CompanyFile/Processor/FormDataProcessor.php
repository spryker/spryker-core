<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor;

use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\FormDataNormalizerInterface;
use Symfony\Component\HttpFoundation\Request;

class FormDataProcessor implements FormDataProcessorInterface
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_FILE_ATTACHMENT = 'fileAttachment';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_SSP_ASSET_FILE_ATTACHMENT = 'sspAssetFileAttachment';

    /**
     * @var string
     */
    protected const FIELD_COMPANY_IDS = 'companyIds';

    /**
     * @var string
     */
    protected const FIELD_COMPANY_USER_IDS = 'companyUserIds';

    /**
     * @var string
     */
    protected const FIELD_COMPANY_BUSINESS_UNIT_IDS = 'companyBusinessUnitIds';

    /**
     * @var string
     */
    protected const FIELD_SSP_ASSET_IDS = 'sspAssetIds';

    public function __construct(
        protected FormDataNormalizerInterface $formDataNormalizer
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>|null
     */
    public function getFormDataFromRequest(Request $request): ?array
    {
        $fileAttachmentData = $request->get(static::REQUEST_PARAM_FILE_ATTACHMENT);
        $sspAssetFileAttachmentData = $request->get(static::REQUEST_PARAM_SSP_ASSET_FILE_ATTACHMENT);

        if ($fileAttachmentData === null && $sspAssetFileAttachmentData === null) {
            return null;
        }

        $fileAttachmentData = $this->formDataNormalizer->cleanFormData($fileAttachmentData ?: []);
        $sspAssetFileAttachmentData = $this->formDataNormalizer->cleanFormData($sspAssetFileAttachmentData ?: []);

        $fileAttachmentData = $this->formDataNormalizer->normalizeFormData($fileAttachmentData);
        $sspAssetFileAttachmentData = $this->formDataNormalizer->normalizeFormData($sspAssetFileAttachmentData);

        return array_merge($fileAttachmentData, $sspAssetFileAttachmentData);
    }

    public function preprocessRequestData(Request $request): void
    {
        if ($request->getMethod() !== Request::METHOD_POST) {
            return;
        }

        $fileAttachmentData = $request->request->all(static::REQUEST_PARAM_FILE_ATTACHMENT);
        $sspAssetData = $request->request->all(static::REQUEST_PARAM_SSP_ASSET_FILE_ATTACHMENT);

        if (!is_array($fileAttachmentData)) {
            $fileAttachmentData = [];
        }
        if (!is_array($sspAssetData)) {
            $sspAssetData = [];
        }

        if (isset($fileAttachmentData[static::FIELD_COMPANY_IDS]) && is_array($fileAttachmentData[static::FIELD_COMPANY_IDS])) {
            $fileAttachmentData[static::FIELD_COMPANY_IDS] = $this->formDataNormalizer->flattenToIndexedArray($fileAttachmentData[static::FIELD_COMPANY_IDS]);
        }
        if (isset($fileAttachmentData[static::FIELD_COMPANY_USER_IDS]) && is_array($fileAttachmentData[static::FIELD_COMPANY_USER_IDS])) {
            $fileAttachmentData[static::FIELD_COMPANY_USER_IDS] = $this->formDataNormalizer->flattenToIndexedArray($fileAttachmentData[static::FIELD_COMPANY_USER_IDS]);
        }
        if (isset($fileAttachmentData[static::FIELD_COMPANY_BUSINESS_UNIT_IDS]) && is_array($fileAttachmentData[static::FIELD_COMPANY_BUSINESS_UNIT_IDS])) {
            $fileAttachmentData[static::FIELD_COMPANY_BUSINESS_UNIT_IDS] = $this->formDataNormalizer->flattenToIndexedArray($fileAttachmentData[static::FIELD_COMPANY_BUSINESS_UNIT_IDS]);
        }

        if (isset($sspAssetData[static::FIELD_SSP_ASSET_IDS]) && is_array($sspAssetData[static::FIELD_SSP_ASSET_IDS])) {
            $sspAssetData[static::FIELD_SSP_ASSET_IDS] = $this->formDataNormalizer->flattenToIndexedArray($sspAssetData[static::FIELD_SSP_ASSET_IDS]);
        }

        $request->request->set(static::REQUEST_PARAM_FILE_ATTACHMENT, $fileAttachmentData);
        $request->request->set(static::REQUEST_PARAM_SSP_ASSET_FILE_ATTACHMENT, $sspAssetData);
    }
}
