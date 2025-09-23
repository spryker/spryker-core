<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\Constraint;

use Generated\Shared\Transfer\DataImportMerchantFileTransfer;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\DataImportMerchantFileForm;
use Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\DataImportMerchantFileInfoForm;
use Spryker\Zed\Kernel\Communication\Validator\AbstractConstraintValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraint;

/**
 * @method \Spryker\Zed\DataImportMerchantPortalGui\Communication\DataImportMerchantPortalGuiCommunicationFactory getFactory()
 */
class CsvHeaderConstraintValidator extends AbstractConstraintValidator
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $value
     * @param \Symfony\Component\Validator\Constraint|\Spryker\Zed\DataImportMerchantPortalGui\Communication\Form\Constraint\CsvHeaderConstraint $constraint
     *
     * @return void
     */
    public function validate(mixed $value, Constraint|CsvHeaderConstraint $constraint): void
    {
        if (!$value instanceof UploadedFile || !$constraint instanceof CsvHeaderConstraint) {
            return;
        }

        $dataImportMerchantFileForm = $this->context->getRoot();
        $importerType = $dataImportMerchantFileForm->get(DataImportMerchantFileTransfer::IMPORTER_TYPE)->getData();

        if (!$importerType) {
            return;
        }

        $forceProceed = $dataImportMerchantFileForm->get(DataImportMerchantFileForm::FIELD_FILE_INFO)
            ->get(DataImportMerchantFileInfoForm::FIELD_FORCE_PROCEED)
            ->getData();

        if ($forceProceed) {
            return;
        }

        if ($value->getMimeType() !== 'text/csv') {
            return;
        }

        /** @var string $fileContent */
        $fileContent = file_get_contents($value->getPathname());
        [$rawHeaders] = explode(PHP_EOL, $fileContent);
        $csvHeaders = str_getcsv($rawHeaders);

        $allPossibleHeadersByImporterType = $constraint->headers[$importerType] ?? [];
        if (!$allPossibleHeadersByImporterType) {
            return;
        }

        $diff = array_diff($csvHeaders, $allPossibleHeadersByImporterType);

        if ($diff) {
            $errorMessage = $this->getFactory()->getTranslatorFacade()->trans(
                $constraint->message,
                ['%headers%' => implode(', ', $diff)],
            );
            $this->context->buildViolation($errorMessage)
                ->setCode('INVALID_CSV_HEADERS')
                ->addViolation();
        }
    }
}
