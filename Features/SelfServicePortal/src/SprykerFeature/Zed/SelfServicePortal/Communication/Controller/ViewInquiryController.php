<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryIncludeTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ViewInquiryController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_SSP_INQUIRY = 'id-ssp-inquiry';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ListInquiryController::indexAction()
     *
     * @var string
     */
    protected const REDIRECT_URL = '/self-service-portal/list-inquiry';

    /**
     * @var string
     */
    protected const MESSAGE_SSP_INQUIRY_NOT_FOUND_ERROR = 'Ssp Inquiry not found';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        $idSspInquiry = $this->castId($request->query->get(static::PARAM_ID_SSP_INQUIRY));

        $sspInquiryCriteriaTransfer = $this->createSspInquiryCriteriaTransfer($idSspInquiry);

        $sspInquiryCollectionTransfer = $this->getFacade()->getSspInquiryCollection(
            $sspInquiryCriteriaTransfer,
        );

        if ($sspInquiryCollectionTransfer->getSspInquiries()->count() === 0) {
            $this->addErrorMessage(static::MESSAGE_SSP_INQUIRY_NOT_FOUND_ERROR);

            return $this->redirectResponse(static::REDIRECT_URL);
        }

        /** @var \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer */
        $sspInquiryTransfer = $sspInquiryCollectionTransfer->getSspInquiries()->getIterator()->current();

        $triggerEventFormOptions = $this->getFactory()->createTriggerEventFormDataProvider()->getOptions($idSspInquiry);
        $triggerEventForm = $this->getFactory()->getTriggerEventForm([], $triggerEventFormOptions);

        return $this->viewResponse([
            'sspInquiry' => $sspInquiryTransfer,
            'triggerEventForm' => $triggerEventForm->createView(),
            'sspInquiryStatusClassMap' => $this->getFactory()->getConfig()->getInquiryStatusClassMap(),
        ]);
    }

    protected function createSspInquiryCriteriaTransfer(int $idSspInquiry): SspInquiryCriteriaTransfer
    {
        $sspInquiryIncludeTransfer = (new SspInquiryIncludeTransfer())
            ->setWithCompanyUser(true)
            ->setWithOrder(true)
            ->setWithFiles(true)
            ->setWithManualEvents(true)
            ->setWithStatusHistory(true)
            ->setWithComments(true);

        return (new SspInquiryCriteriaTransfer())->setSspInquiryConditions(
            (new SspInquiryConditionsTransfer())->addIdSspInquiry($idSspInquiry),
        )->setInclude(
            $sspInquiryIncludeTransfer,
        );
    }
}
