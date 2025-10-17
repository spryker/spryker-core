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
    protected const FILENAME_EXAMPLE_ASSET_ATTACHMENTS = 'asset_file_attachment_example.csv';

    /**
     * @var string
     */
    protected const FILENAME_EXAMPLE_BUSINESS_UNIT_ATTACHMENTS = 'business_unit_file_attachment_example.csv';

    /**
     * @var string
     */
    protected const FILENAME_EXAMPLE_COMPANY_USER_ATTACHMENTS = 'company_user_file_attachment_example.csv';

    /**
     * @var string
     */
    protected const FILENAME_EXAMPLE_COMPANY_ATTACHMENTS = 'company_file_attachment_example.csv';

    /**
     * @var string
     */
    protected const FILENAME_EXAMPLE_MODEL_ATTACHMENTS = 'model_file_attachment_example.csv';

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
    protected const EXAMPLE_ASSET_CSV_ATTACHMENT_FILE_CONTENT = "Asset to be attached,Asset to be detached\nAST--1,AST--4\nAST--2,AST--5\nAST--3,AST--6";

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
        return $this->createCsvDownloadResponse(static::EXAMPLE_ASSET_CSV_ATTACHMENT_FILE_CONTENT, static::FILENAME_EXAMPLE_ASSET_ATTACHMENTS);
    }

    public function downloadBusinessUnitExampleAction(Request $request): StreamedResponse
    {
        return $this->createCsvDownloadResponse(static::EXAMPLE_BUSINESS_UNIT_CSV_ATTACHMENT_FILE_CONTENT, static::FILENAME_EXAMPLE_BUSINESS_UNIT_ATTACHMENTS);
    }

    public function downloadCompanyUserExampleAction(Request $request): StreamedResponse
    {
        return $this->createCsvDownloadResponse(static::EXAMPLE_COMPANY_USER_CSV_ATTACHMENT_FILE_CONTENT, static::FILENAME_EXAMPLE_COMPANY_USER_ATTACHMENTS);
    }

    public function downloadCompanyExampleAction(Request $request): StreamedResponse
    {
        return $this->createCsvDownloadResponse(static::EXAMPLE_COMPANY_CSV_ATTACHMENT_FILE_CONTENT, static::FILENAME_EXAMPLE_COMPANY_ATTACHMENTS);
    }

    public function downloadModelExampleAction(Request $request): StreamedResponse
    {
        return $this->createCsvDownloadResponse(static::EXAMPLE_MODEL_CSV_ATTACHMENT_FILE_CONTENT, static::FILENAME_EXAMPLE_MODEL_ATTACHMENTS);
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
