<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Exception;
use Generated\Shared\Transfer\BusinessUnitAssignmentFileParserRequestTransfer;
use Generated\Shared\Transfer\CompanyAssignmentFileParserRequestTransfer;
use Generated\Shared\Transfer\CompanyUserAssignmentFileParserRequestTransfer;
use Generated\Shared\Transfer\SspAssetAssignmentFileParserRequestTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ImportFileAttachmentController extends FileAbstractController
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const FILE_PARAM_SSP_ASSET_ASSIGNMENT = 'ssp-asset-assignment-file';

    /**
     * @var string
     */
    protected const FILE_PARAM_BUSINESS_UNIT_ASSIGNMENT = 'business-unit-assignment-file';

    /**
     * @var string
     */
    protected const FILE_PARAM_COMPANY_USER_ASSIGNMENT = 'company-user-assignment-file';

    /**
     * @var string
     */
    protected const FILE_PARAM_COMPANY_ASSIGNMENT = 'company-assignment-file';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_VALIDATION_FAILED = 'Validation failed';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FAILED_TO_READ_FILE = 'Failed to read file: %s';

    /**
     * @var string
     */
    protected const SUCCESS_MESSAGE_FILE_PROCESSED = 'File processed successfully. Found %d %s to assign and %d to deassign.';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_ERROR = 'error';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_SUCCESS = 'success';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_MESSAGE = 'message';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_DATA = 'data';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_IDS_TO_ASSIGN = 'idsToAssign';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_IDS_TO_DEASSIGN = 'idsToDeassign';

    /**
     * @var string
     */
    protected const ENTITY_TYPE_COMPANY_USERS = 'company users';

    /**
     * @var string
     */
    protected const ENTITY_TYPE_COMPANIES = 'companies';

    /**
     * @var string
     */
    protected const VALIDATION_KEY_VALID = 'valid';

    /**
     * @var string
     */
    protected const LOG_MESSAGE_ASSET_ASSIGNMENT_IMPORT_FAILED = 'Asset assignment import failed for file ID %d: %s';

    /**
     * @var string
     */
    protected const LOG_MESSAGE_BUSINESS_UNIT_ASSIGNMENT_IMPORT_FAILED = 'Business unit assignment import failed for file ID %d: %s';

    /**
     * @var string
     */
    protected const LOG_MESSAGE_COMPANY_USER_ASSIGNMENT_IMPORT_FAILED = 'Company user assignment import failed for file ID %d: %s';

    /**
     * @var string
     */
    protected const LOG_MESSAGE_COMPANY_ASSIGNMENT_IMPORT_FAILED = 'Company assignment import failed for file ID %d: %s';

    /**
     * @var string
     */
    protected const LOG_MESSAGE_FILE_VALIDATION_FAILED = 'File validation failed for import: %s';

    public function importAssetAssignmentsAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));
        $uploadedFile = $request->files->get(static::FILE_PARAM_SSP_ASSET_ASSIGNMENT);

        $validator = $this->getFactory()->createFileSecurityValidator();
        $validation = $validator->validateUploadedFile($uploadedFile);

        if (!$validation[static::VALIDATION_KEY_VALID]) {
            $errorMessage = $validation[static::RESPONSE_KEY_ERROR] ?? static::ERROR_MESSAGE_VALIDATION_FAILED;
            $this->getLogger()->error(sprintf(static::LOG_MESSAGE_FILE_VALIDATION_FAILED, $errorMessage), [
                'fileId' => $idFile,
                'validationErrors' => $validation,
            ]);

            return $this->jsonResponse([static::RESPONSE_KEY_ERROR => $errorMessage], Response::HTTP_BAD_REQUEST);
        }

        try {
            $content = $validator->readFileSecurely($uploadedFile->getPathname());

            $sspAssetAssignmentFileParserRequestTransfer = (new SspAssetAssignmentFileParserRequestTransfer())->setContent($content);
            $sspAssetAssignmentFileParserResponseTransfer = $this->getFactory()->createAssetAssignmentFileParser()->parse($sspAssetAssignmentFileParserRequestTransfer);

            $assetIdsToAssign = $this->getFactory()->createAssetAssignmentImporter()->getIdsToAssign($sspAssetAssignmentFileParserResponseTransfer, $idFile);
            $assetIdsToDeassign = $this->getFactory()->createAssetAssignmentImporter()->getIdsToUnassign($sspAssetAssignmentFileParserResponseTransfer, $idFile);

            return $this->jsonResponse([
                static::RESPONSE_KEY_IDS_TO_ASSIGN => $assetIdsToAssign,
                static::RESPONSE_KEY_IDS_TO_DEASSIGN => $assetIdsToDeassign,
            ]);
        } catch (Exception $e) {
            $this->getLogger()->error(sprintf(static::LOG_MESSAGE_ASSET_ASSIGNMENT_IMPORT_FAILED, $idFile, $e->getMessage()), [
                'exception' => $e,
                'fileId' => $idFile,
            ]);

            return $this->jsonResponse([static::RESPONSE_KEY_ERROR => sprintf(static::ERROR_MESSAGE_FAILED_TO_READ_FILE, $e->getMessage())], Response::HTTP_BAD_REQUEST);
        }
    }

    public function importBusinessUnitAssignmentsAction(Request $request): JsonResponse
    {
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));
        $uploadedFile = $request->files->get(static::FILE_PARAM_BUSINESS_UNIT_ASSIGNMENT);

        $validator = $this->getFactory()->createFileSecurityValidator();
        $validation = $validator->validateUploadedFile($uploadedFile);

        if (!$validation[static::VALIDATION_KEY_VALID]) {
            $errorMessage = $validation[static::RESPONSE_KEY_ERROR] ?? static::ERROR_MESSAGE_VALIDATION_FAILED;
            $this->getLogger()->error(sprintf(static::LOG_MESSAGE_FILE_VALIDATION_FAILED, $errorMessage), [
                'fileId' => $idFile,
                'validationErrors' => $validation,
            ]);

            return $this->jsonResponse([static::RESPONSE_KEY_ERROR => $errorMessage], Response::HTTP_BAD_REQUEST);
        }

        try {
            $content = $validator->readFileSecurely($uploadedFile->getPathname());

            $businessUnitAssignmentFileParserRequestTransfer = (new BusinessUnitAssignmentFileParserRequestTransfer())->setContent($content);
            $businessUnitAssignmentFileParserResponseTransfer = $this->getFactory()->createBusinessUnitAssignmentFileParser()->parse($businessUnitAssignmentFileParserRequestTransfer);

            $businessUnitIdsToAssign = $this->getFactory()->createBusinessUnitAssignmentImporter()->getIdsToAssign($businessUnitAssignmentFileParserResponseTransfer, $idFile);
            $businessUnitIdsToDeassign = $this->getFactory()->createBusinessUnitAssignmentImporter()->getIdsToUnassign($businessUnitAssignmentFileParserResponseTransfer, $idFile);

            return $this->jsonResponse([
                static::RESPONSE_KEY_IDS_TO_ASSIGN => $businessUnitIdsToAssign,
                static::RESPONSE_KEY_IDS_TO_DEASSIGN => $businessUnitIdsToDeassign,
            ]);
        } catch (Exception $e) {
            $this->getLogger()->error(sprintf(static::LOG_MESSAGE_BUSINESS_UNIT_ASSIGNMENT_IMPORT_FAILED, $idFile, $e->getMessage()), [
                'exception' => $e,
                'fileId' => $idFile,
            ]);

            return $this->jsonResponse([static::RESPONSE_KEY_ERROR => sprintf(static::ERROR_MESSAGE_FAILED_TO_READ_FILE, $e->getMessage())], Response::HTTP_BAD_REQUEST);
        }
    }

    public function importCompanyUserAssignmentsAction(Request $request): JsonResponse
    {
        $uploadedFile = $request->files->get(static::FILE_PARAM_COMPANY_USER_ASSIGNMENT);
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        $validator = $this->getFactory()->createFileSecurityValidator();
        $validation = $validator->validateUploadedFile($uploadedFile);

        if (!$validation[static::VALIDATION_KEY_VALID]) {
            $errorMessage = $validation[static::RESPONSE_KEY_ERROR] ?? static::ERROR_MESSAGE_VALIDATION_FAILED;
            $this->getLogger()->error(sprintf(static::LOG_MESSAGE_FILE_VALIDATION_FAILED, $errorMessage), [
                'fileId' => $idFile,
                'validationErrors' => $validation,
            ]);

            return $this->jsonResponse([static::RESPONSE_KEY_ERROR => $errorMessage], Response::HTTP_BAD_REQUEST);
        }

        try {
            $content = $validator->readFileSecurely($uploadedFile->getPathname());

            $companyUserAssignmentFileParserRequestTransfer = (new CompanyUserAssignmentFileParserRequestTransfer())->setFileContent($content);
            $companyUserAssignmentFileParserResponseTransfer = $this->getFactory()->createCompanyUserAssignmentFileParser()->parseFile($companyUserAssignmentFileParserRequestTransfer);

            $importer = $this->getFactory()->createCompanyUserAssignmentImporter();
            $idsToAssign = $importer->getIdsToAssign($companyUserAssignmentFileParserResponseTransfer, $idFile);
            $idsToDeassign = $importer->getIdsToUnassign($companyUserAssignmentFileParserResponseTransfer, $idFile);

            return $this->jsonResponse([
                static::RESPONSE_KEY_SUCCESS => true,
                static::RESPONSE_KEY_MESSAGE => sprintf(static::SUCCESS_MESSAGE_FILE_PROCESSED, count($idsToAssign), static::ENTITY_TYPE_COMPANY_USERS, count($idsToDeassign)),
                static::RESPONSE_KEY_DATA => [static::RESPONSE_KEY_IDS_TO_ASSIGN => $idsToAssign, static::RESPONSE_KEY_IDS_TO_DEASSIGN => $idsToDeassign],
            ]);
        } catch (Exception $e) {
            $this->getLogger()->error(sprintf(static::LOG_MESSAGE_COMPANY_USER_ASSIGNMENT_IMPORT_FAILED, $idFile, $e->getMessage()), [
                'exception' => $e,
                'fileId' => $idFile,
            ]);

            return $this->jsonResponse([static::RESPONSE_KEY_ERROR => sprintf(static::ERROR_MESSAGE_FAILED_TO_READ_FILE, $e->getMessage())], Response::HTTP_BAD_REQUEST);
        }
    }

    public function importCompanyAssignmentsAction(Request $request): JsonResponse
    {
        $uploadedFile = $request->files->get(static::FILE_PARAM_COMPANY_ASSIGNMENT);
        $idFile = $this->castId($request->get(static::REQUEST_PARAM_ID_FILE));

        $validator = $this->getFactory()->createFileSecurityValidator();
        $validation = $validator->validateUploadedFile($uploadedFile);

        if (!$validation[static::VALIDATION_KEY_VALID]) {
            $errorMessage = $validation[static::RESPONSE_KEY_ERROR] ?? static::ERROR_MESSAGE_VALIDATION_FAILED;
            $this->getLogger()->error(sprintf(static::LOG_MESSAGE_FILE_VALIDATION_FAILED, $errorMessage), [
                'fileId' => $idFile,
                'validationErrors' => $validation,
            ]);

            return $this->jsonResponse([static::RESPONSE_KEY_ERROR => $errorMessage], Response::HTTP_BAD_REQUEST);
        }

        try {
            $content = $validator->readFileSecurely($uploadedFile->getPathname());

            $companyAssignmentFileParserRequestTransfer = (new CompanyAssignmentFileParserRequestTransfer())->setFileContent($content);
            $companyAssignmentFileParserResponseTransfer = $this->getFactory()->createCompanyAssignmentFileParser()->parseFile($companyAssignmentFileParserRequestTransfer);

            $importer = $this->getFactory()->createCompanyAssignmentImporter();
            $idsToAssign = $importer->getIdsToAssign($companyAssignmentFileParserResponseTransfer, $idFile);
            $idsToDeassign = $importer->getIdsToUnassign($companyAssignmentFileParserResponseTransfer, $idFile);

            return $this->jsonResponse([
                static::RESPONSE_KEY_SUCCESS => true,
                static::RESPONSE_KEY_MESSAGE => sprintf(static::SUCCESS_MESSAGE_FILE_PROCESSED, count($idsToAssign), static::ENTITY_TYPE_COMPANIES, count($idsToDeassign)),
                static::RESPONSE_KEY_DATA => [static::RESPONSE_KEY_IDS_TO_ASSIGN => $idsToAssign, static::RESPONSE_KEY_IDS_TO_DEASSIGN => $idsToDeassign],
            ]);
        } catch (Exception $e) {
            $this->getLogger()->error(sprintf(static::LOG_MESSAGE_COMPANY_ASSIGNMENT_IMPORT_FAILED, $idFile, $e->getMessage()), [
                'exception' => $e,
                'fileId' => $idFile,
            ]);

            return $this->jsonResponse([static::RESPONSE_KEY_ERROR => sprintf(static::ERROR_MESSAGE_FAILED_TO_READ_FILE, $e->getMessage())], Response::HTTP_BAD_REQUEST);
        }
    }
}
