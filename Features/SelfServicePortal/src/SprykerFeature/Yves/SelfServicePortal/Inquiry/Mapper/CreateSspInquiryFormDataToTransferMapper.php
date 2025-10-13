<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Mapper;

use ArrayObject;
use Exception;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\FileUploadTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\Customer\CustomerClientInterface;
use SprykerFeature\Yves\SelfServicePortal\Inquiry\Form\SspInquiryForm;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class CreateSspInquiryFormDataToTransferMapper implements CreateSspInquiryFormDataToTransferMapperInterface
{
    public function __construct(
        protected CompanyUserClientInterface $companyUserClient,
        protected CustomerClientInterface $customerClient
    ) {
    }

    /**
     * @param array<string, mixed> $formData
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    public function mapSspInquiryData(array $formData): SspInquiryTransfer
    {
        $fileManagerDataTransfers = $this->prepareFileManagerDataTransfers($formData[SspInquiryForm::FIELD_FILES]);

        $companyUserTransfer = $this->companyUserClient->findCompanyUser();

        if (!$companyUserTransfer) {
            throw new Exception('self_service_portal.inquiry.error.company_user_not_found');
        }
        $companyUserTransfer->setCustomer($this->customerClient->getCustomerById($companyUserTransfer->getFkCustomerOrFail()));

        $sspInquiryTransfer = (new SspInquiryTransfer())
            ->fromArray($formData, true)
            ->setCompanyUser($companyUserTransfer)
            ->setFiles(new ArrayObject($fileManagerDataTransfers));

        if (isset($formData['orderReference'])) {
            $sspInquiryTransfer->setOrder((new OrderTransfer())
                ->setOrderReference($formData['orderReference'])
                ->setCustomerReference($companyUserTransfer->getCustomerOrFail()->getCustomerReference()));
        }

        if (isset($formData['sspAssetReference'])) {
            $sspInquiryTransfer->setSspAsset((new SspAssetTransfer())->setReference($formData['sspAssetReference']));
        }

        return $sspInquiryTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\FileUploadTransfer> $fileUploadTransfers
     *
     * @return array<\Generated\Shared\Transfer\FileTransfer>
     */
    protected function prepareFileManagerDataTransfers(array $fileUploadTransfers): array
    {
        $fileManagerDataTransfers = [];

        foreach ($fileUploadTransfers as $fileUploadTransfer) {
            $fileManagerDataTransfers[] = (new FileTransfer())
                ->setFileUpload($fileUploadTransfer)
                ->setEncodedContent(base64_encode(gzencode($this->getFileContent($fileUploadTransfer)) ?: ''));
        }

        return $fileManagerDataTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\FileUploadTransfer $fileUploadTransfer
     *
     * @throws \Exception
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     *
     * @return string
     */
    protected function getFileContent(FileUploadTransfer $fileUploadTransfer): string
    {
        $realPath = $fileUploadTransfer->getRealPath();

        if ($realPath === null) {
            throw new Exception('self_service_portal.inquiry.file.file_not_found');
        }

        $fileContent = file_get_contents($realPath);

        if ($fileContent === false) {
            throw new FileNotFoundException($realPath);
        }

        return $fileContent;
    }
}
