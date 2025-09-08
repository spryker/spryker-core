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
    protected const FILENAME_EXAMPLE_ASSET_ASSIGNMENTS = 'example-asset-assignments.csv';

    /**
     * @var string
     */
    protected const FILENAME_EXAMPLE_BUSINESS_UNIT_ASSIGNMENTS = 'example-business-unit-assignments.csv';

    /**
     * @var string
     */
    protected const FILENAME_COMPANY_USER_ASSIGNMENT_EXAMPLE = 'company_user_assignment_example.csv';

    /**
     * @var string
     */
    protected const FILENAME_COMPANY_ASSIGNMENT_EXAMPLE = 'company_assignment_example.csv';

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

    public function downloadExampleAction(Request $request): StreamedResponse
    {
        $content = "Asset to be attached\nASSET-REF-1\nASSET-REF-2\nAsset to be detached\nASSET-REF-3\nASSET-REF-4";

        return $this->createCsvDownloadResponse($content, static::FILENAME_EXAMPLE_ASSET_ASSIGNMENTS);
    }

    public function downloadBusinessUnitExampleAction(Request $request): StreamedResponse
    {
        $content = "Business unit to be attached\n1\n2\nBusiness unit to be detached\n3\n4";

        return $this->createCsvDownloadResponse($content, static::FILENAME_EXAMPLE_BUSINESS_UNIT_ASSIGNMENTS);
    }

    public function downloadCompanyUserExampleAction(Request $request): StreamedResponse
    {
        $csvContent = "Company user to be attached\n123\n456\nCompany user to be detached\n789\n101\n";

        return $this->createCsvDownloadResponse($csvContent, static::FILENAME_COMPANY_USER_ASSIGNMENT_EXAMPLE);
    }

    public function downloadCompanyExampleAction(Request $request): StreamedResponse
    {
        $csvContent = "Company to be attached\n123\n456\nCompany to be detached\n789\n101\n";

        return $this->createCsvDownloadResponse($csvContent, static::FILENAME_COMPANY_ASSIGNMENT_EXAMPLE);
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
