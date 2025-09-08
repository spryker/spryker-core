<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy;

use Generated\Shared\Transfer\CompanyUserAssignmentFileParserRequestTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\AttachFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\CompanyUserAssignmentImporterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\CompanyUserAssignmentFileParserInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CompanyUserCsvImportStrategy implements CsvImportStrategyInterface
{
    /**
     * @var string
     */
    protected const SCOPE_COMPANY_USER = 'company-user';

    /**
     * @var string
     */
    protected const KEY_COMPANY_USER_IDS_TO_BE_ASSIGNED = 'companyUserIdsToBeAssigned';

    /**
     * @var string
     */
    protected const KEY_COMPANY_USER_IDS_TO_BE_DEASSIGNED = 'companyUserIdsToBeDeassigned';

    public function __construct(
        protected CompanyUserAssignmentFileParserInterface $companyUserAssignmentFileParser,
        protected CompanyUserAssignmentImporterInterface $companyUserAssignmentImporter
    ) {
    }

    public function canHandle(string $scope): bool
    {
        return $scope === static::SCOPE_COMPANY_USER;
    }

    public function extractFileFromForm(FormInterface $form): ?UploadedFile
    {
        return $form->get(AttachFileForm::FIELD_COMPANY_USER_FILE_UPLOAD)->getData();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param int $idFile
     *
     * @return array<string, mixed>
     */
    public function processCsvFile(UploadedFile $file, int $idFile): array
    {
        $content = file_get_contents($file->getPathname());
        if ($content === false) {
            return [];
        }
        $companyUserAssignmentFileParserRequestTransfer = (new CompanyUserAssignmentFileParserRequestTransfer())->setFileContent($content);
        $companyUserReferences = $this->companyUserAssignmentFileParser->parseFile($companyUserAssignmentFileParserRequestTransfer);

        $companyUserIdsToAssign = $this->companyUserAssignmentImporter->getIdsToAssign($companyUserReferences, $idFile);
        $companyUserIdsToDeassign = $this->companyUserAssignmentImporter->getIdsToUnassign($companyUserReferences, $idFile);

        return [
            static::KEY_COMPANY_USER_IDS_TO_BE_ASSIGNED => $companyUserIdsToAssign,
            static::KEY_COMPANY_USER_IDS_TO_BE_DEASSIGNED => $companyUserIdsToDeassign,
        ];
    }
}
