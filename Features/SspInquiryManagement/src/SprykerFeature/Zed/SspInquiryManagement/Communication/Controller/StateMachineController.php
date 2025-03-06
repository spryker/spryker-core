<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Controller;

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
 * @method \SprykerFeature\Zed\SspInquiryManagement\Communication\SspInquiryManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface getRepository()
 */
class StateMachineController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID = 'id-ssp-inquiry';

    /**
     * @var string
     */
    protected const ROUTE_SSP_INQUIRY_DETAIL_PAGE = '/ssp-inquiry-management/detail/index';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function triggerAction(Request $request): RedirectResponse
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
            $this->addErrorMessage('Ssp Inquiry status transition is not possible.');
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
            $this->addSuccessMessage('Ssp Inquiry status changed successfully.');
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
            ->setIdItemState($sspInquiryTransfer->getFkStateMachineItemState())
            ->setStateMachineName(
                $config->getSspInquiryStateMachineName(),
            )
            ->setProcessName(
                $config->getSspInquiryStateMachineProcessSspInquiryTypeMap()[$sspInquiryTransfer->getType()],
            );

        return $stateMachineItemTransfer;
    }
}
