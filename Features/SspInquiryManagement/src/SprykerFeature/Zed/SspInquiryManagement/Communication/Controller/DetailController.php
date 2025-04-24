<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Controller;

use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryIncludeTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Communication\SspInquiryManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface getRepository()
 */
class DetailController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_SSP_INQUIRY = 'id-ssp-inquiry';

    /**
     * @var string
     */
    protected const REDIRECT_URL = '/ssp-inquiry-management';

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
        $sspInquiryCollectionTransfer = $this->getFacade()->getSspInquiryCollection(
            (new SspInquiryCriteriaTransfer())->setSspInquiryConditions(
                (new SspInquiryConditionsTransfer())->addIdSspInquiry($idSspInquiry),
            )->setInclude(
                (new SspInquiryIncludeTransfer())
                    ->setWithCompanyUser(true)
                    ->setWithOrder(true)
                    ->setWithFiles(true)
                    ->setWithManualEvents(true)
                    ->setWithStatusHistory(true)
                    ->setWithComments(true),
            ),
        );

        if ($sspInquiryCollectionTransfer->getSspInquiries()->count() === 0) {
            $this->addErrorMessage(static::MESSAGE_SSP_INQUIRY_NOT_FOUND_ERROR);

            return $this->redirectResponse(static::REDIRECT_URL);
        }

        /** @var \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer */
         $sspInquiryTransfer = $sspInquiryCollectionTransfer->getSspInquiries()->getIterator()->current();

        return $this->viewResponse([
            'sspInquiry' => $sspInquiryTransfer,
            'triggerEventForm' => $this->getFactory()->getTriggerEventForm(
                [],
                $this->getFactory()->createTriggerEventFormDataProvider()->getOptions($idSspInquiry),
            )->createView(),
            'sspInquiryStatusClassMap' => $this->getFactory()->getConfig()->getSspInquiryStatusClassMap(),
        ]);
    }
}
