<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ListInquiryController extends AbstractGatewayController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
         $sspInquiryFilterForm = $this->getFactory()->getSspInquiryFilterForm(
             (new SspInquiryConditionsTransfer()),
         );

         $sspInquiryFilterForm->handleRequest($request);

         $sspInquiryCriteriaTransfer = $sspInquiryFilterForm->isSubmitted() && $sspInquiryFilterForm->isValid()
            ? $sspInquiryFilterForm->getData()
            : new SspInquiryConditionsTransfer();

         $sspInquiryTable = $this
            ->getFactory()
            ->createSspInquiryTable($sspInquiryCriteriaTransfer);

        return $this->viewResponse([
            'sspInquiryTable' => $sspInquiryTable->render(),
            'sspInquiryFilterForm' => $sspInquiryFilterForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
         $sspInquiryFilterForm = $this->getFactory()->getSspInquiryFilterForm(new SspInquiryConditionsTransfer());

         $sspInquiryFilterForm->handleRequest($request);

         $sspInquiryCriteriaTransfer = $sspInquiryFilterForm->isSubmitted() && $sspInquiryFilterForm->isValid()
            ? $sspInquiryFilterForm->getData()
            : new SspInquiryConditionsTransfer();

         $sspInquiryTable = $this
            ->getFactory()
            ->createSspInquiryTable($sspInquiryCriteriaTransfer);

        return $this->jsonResponse(
            $sspInquiryTable->fetchData(),
        );
    }
}
