<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class FileAttachmentFormAutocompleteController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_TERM = 'term';

    public function companyAction(Request $request): JsonResponse
    {
        $term = (string)$request->query->get(static::REQUEST_PARAM_TERM, '');
        $options = $this->getFactory()
            ->createFileAttachFormDataProvider()
            ->getCompanyAutocompleteData($term);

        return $this->jsonResponse($options);
    }

    public function companyUserAction(Request $request): JsonResponse
    {
        $term = (string)$request->query->get(static::REQUEST_PARAM_TERM, '');
        $options = $this->getFactory()
            ->createFileAttachFormDataProvider()
            ->getCompanyUserAutocompleteData($term);

        return $this->jsonResponse($options);
    }

    public function companyBusinessUnitAction(Request $request): JsonResponse
    {
        $term = (string)$request->query->get(static::REQUEST_PARAM_TERM, '');
        $options = $this->getFactory()
            ->createFileAttachFormDataProvider()
            ->getCompanyBusinessUnitAutocompleteData($term);

        return $this->jsonResponse($options);
    }
}
