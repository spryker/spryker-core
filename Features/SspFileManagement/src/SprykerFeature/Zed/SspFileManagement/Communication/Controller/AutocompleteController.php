<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 */
class AutocompleteController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_TERM = 'term';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function companyAction(Request $request): JsonResponse
    {
        $term = (string)$request->query->get(static::REQUEST_PARAM_TERM, '');
        $options = $this->getFactory()
            ->createFileAttachFormDataProvider()
            ->getCompanyAutocompleteData($term);

        return $this->jsonResponse($options);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function companyUserAction(Request $request): JsonResponse
    {
        $term = (string)$request->query->get(static::REQUEST_PARAM_TERM, '');
        $options = $this->getFactory()
            ->createFileAttachFormDataProvider()
            ->getCompanyUserAutocompleteData($term);

        return $this->jsonResponse($options);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function companyBusinessUnitAction(Request $request): JsonResponse
    {
        $term = (string)$request->query->get(static::REQUEST_PARAM_TERM, '');
        $options = $this->getFactory()
            ->createFileAttachFormDataProvider()
            ->getCompanyBusinessUnitAutocompleteData($term);

        return $this->jsonResponse($options);
    }
}
