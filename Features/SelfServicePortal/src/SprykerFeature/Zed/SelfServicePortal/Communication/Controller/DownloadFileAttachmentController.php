<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class DownloadFileAttachmentController extends FileAbstractController
{
    /**
     * @var string
     */
    protected const FILENAME_EXAMPLE_ASSET_ASSIGNMENTS = 'asset_file_assignments_example.csv';

    /**
     * @var string
     */
    protected const FILENAME_EXAMPLE_BUSINESS_UNIT_ASSIGNMENTS = 'business_unit_file_assignments_example.csv';

    /**
     * @var string
     */
    protected const FILENAME_COMPANY_USER_ASSIGNMENT_EXAMPLE = 'company_user_file_assignment_example.csv';

    /**
     * @var string
     */
    protected const FILENAME_COMPANY_ASSIGNMENT_EXAMPLE = 'company_file_assignment_example.csv';

    /**
     * @var string
     */
    protected const FILENAME_MODEL_ASSIGNMENT_EXAMPLE = 'model_file_assignment_example.csv';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_TYPE_CSV = 'text/csv';

    /**
     * @var string
     */
    protected const HEADER_CACHE_CONTROL_NO_CACHE = 'no-cache, no-store, must-revalidate';

    /**
     * @var string
     */
    protected const HEADER_PRAGMA_NO_CACHE = 'no-cache';

    /**
     * @var string
     */
    protected const HEADER_EXPIRES_IMMEDIATE = '0';

    /**
     * @var string
     */
    protected const EXAMPLE_ASSET_CSV_ATTACHMENT_FILE_CONTENT = "Asset to be attached,Asset to be detached\nASSET-REF-1,ASSET-REF-4\nASSET-REF-2,ASSET-REF-5\nASSET-REF-3,ASSET-REF-6";

    /**
     * @var string
     */
    protected const EXAMPLE_BUSINESS_UNIT_CSV_ATTACHMENT_FILE_CONTENT = "Business unit to be attached,Business unit to be detached\n1,4\n2,5\n3,6";

    /**
     * @var string
     */
    protected const EXAMPLE_COMPANY_USER_CSV_ATTACHMENT_FILE_CONTENT = "Company user to be attached,Company user to be detached\n1,4\n2,5\n3,6\n";

    /**
     * @var string
     */
    protected const EXAMPLE_COMPANY_CSV_ATTACHMENT_FILE_CONTENT = "Company to be attached,Company to be detached\n1,4\n2,5\n3,6\n";

    /**
     * @var string
     */
    protected const EXAMPLE_MODEL_CSV_ATTACHMENT_FILE_CONTENT = "Model to be attached,Model to be detached\nMDL--1,MDL--4\nMDL--2,MDL--5\nMDL--3,MDL--6\n";

    public function downloadExampleAction(Request $request): StreamedResponse
    {
        return $this->createCsvDownloadResponse(static::EXAMPLE_ASSET_CSV_ATTACHMENT_FILE_CONTENT, static::FILENAME_EXAMPLE_ASSET_ASSIGNMENTS);
    }

    public function downloadBusinessUnitExampleAction(Request $request): StreamedResponse
    {
        return $this->createCsvDownloadResponse(static::EXAMPLE_BUSINESS_UNIT_CSV_ATTACHMENT_FILE_CONTENT, static::FILENAME_EXAMPLE_BUSINESS_UNIT_ASSIGNMENTS);
    }

    public function downloadCompanyUserExampleAction(Request $request): StreamedResponse
    {
        return $this->createCsvDownloadResponse(static::EXAMPLE_COMPANY_USER_CSV_ATTACHMENT_FILE_CONTENT, static::FILENAME_COMPANY_USER_ASSIGNMENT_EXAMPLE);
    }

    public function downloadCompanyExampleAction(Request $request): StreamedResponse
    {
        return $this->createCsvDownloadResponse(static::EXAMPLE_COMPANY_CSV_ATTACHMENT_FILE_CONTENT, static::FILENAME_COMPANY_ASSIGNMENT_EXAMPLE);
    }

    public function downloadModelExampleAction(Request $request): StreamedResponse
    {
        return $this->createCsvDownloadResponse(static::EXAMPLE_MODEL_CSV_ATTACHMENT_FILE_CONTENT, static::FILENAME_MODEL_ASSIGNMENT_EXAMPLE);
    }

    protected function createCsvDownloadResponse(string $content, string $filename): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($content): void {
            echo $content;
        });
        $response->headers->set('Content-Type', static::HEADER_CONTENT_TYPE_CSV);
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', static::HEADER_CACHE_CONTROL_NO_CACHE);
        $response->headers->set('Pragma', static::HEADER_PRAGMA_NO_CACHE);
        $response->headers->set('Expires', static::HEADER_EXPIRES_IMMEDIATE);

        return $response;
    }
}
