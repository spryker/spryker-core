<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 */
class TriggerInquiryStateMachineController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID = 'id-ssp-inquiry';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ViewInquiryController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_SSP_INQUIRY_DETAIL_PAGE = '/self-service-portal/view-inquiry';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idSspInquiry = $this->castId($request->query->get(static::PARAM_ID));
        $options = $this->getFactory()->createTriggerEventFormDataProvider()->getOptions(
            $idSspInquiry,
        );

        /**
         * @var \Symfony\Component\Form\Form $triggerEventForm
         */
        $triggerEventForm = $this->getFactory()->getTriggerEventForm([], $options);

        $triggerEventForm->handleRequest($request);

        $isSuccessful = false;

        if ($triggerEventForm->isSubmitted() && $triggerEventForm->isValid()) {
            $isSuccessful = $this->executeTriggerAction($idSspInquiry, $triggerEventForm);
        }

        if (!$isSuccessful) {
            $this->addErrorMessage('Inquiry status transition is not possible.');
        }

        return $this->redirectResponse(
            Url::generate(static::ROUTE_SSP_INQUIRY_DETAIL_PAGE, [
                static::PARAM_ID => $idSspInquiry,
            ])->build(),
        );
    }

    /**
     * @param int $idSspInquiry
     * @param \Symfony\Component\Form\Form $triggerEventForm
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return bool
     */
    protected function executeTriggerAction(int $idSspInquiry, Form $triggerEventForm): bool
    {
         $sspInquiryCollectionTransfer = $this->getFacade()->getSspInquiryCollection(
             (new SspInquiryCriteriaTransfer())
                ->setSspInquiryConditions(
                    (new SspInquiryConditionsTransfer())
                        ->setSspInquiryIds([
                            $idSspInquiry,
                        ]),
                ),
         );

        if (!count($sspInquiryCollectionTransfer->getSspInquiries())) {
            throw new NotFoundHttpException();
        }

        /** @var \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer */
         $sspInquiryTransfer = $sspInquiryCollectionTransfer->getSspInquiries()->offsetGet(0);

        $stateMachineItemTransfer = $this->createStateMachineItemTransfer($sspInquiryTransfer);

        $isSuccessful = false;
        foreach ($triggerEventForm->getIterator() as $formField) {
            /**
             * @var \Symfony\Component\Form\SubmitButton $formField
             */
            if (!$formField->isClicked()) {
                continue;
            }

            $isSuccessful = $this->getFactory()
                ->getStateMachineFacade()
                ->triggerEvent(
                    $formField->getName(),
                    $stateMachineItemTransfer,
                );
        }

        if ($isSuccessful) {
            $this->addSuccessMessage('Inquiry status changed successfully.');
        }

        return (bool)$isSuccessful;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\StateMachineItemTransfer
     */
    protected function createStateMachineItemTransfer(SspInquiryTransfer $sspInquiryTransfer): StateMachineItemTransfer
    {
        $config = $this->getFactory()->getConfig();
        $stateMachineItemTransfer = new StateMachineItemTransfer();
        $stateMachineItemTransfer->setIdentifier($sspInquiryTransfer->getIdSspInquiry())
            ->setIdItemState((int)$sspInquiryTransfer->getStateMachineItemStateOrFail()->getIdStateMachineItemStateOrFail())
            ->setStateMachineName(
                $config->getInquiryStateMachineName(),
            )
            ->setProcessName(
                $config->getSspInquiryStateMachineProcessInquiryTypeMap()[$sspInquiryTransfer->getType()],
            );

        return $stateMachineItemTransfer;
    }
}
