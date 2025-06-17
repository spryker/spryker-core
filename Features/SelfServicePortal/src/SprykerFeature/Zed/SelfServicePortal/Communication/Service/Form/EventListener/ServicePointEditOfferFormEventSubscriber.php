<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\EventListener;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\CreateOfferForm;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ServicePointEditOfferFormEventSubscriber implements EventSubscriberInterface
{
    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::POST_SET_DATA => 'expandFormWithServices',
            FormEvents::SUBMIT => 'expandProductOfferWithServices',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function expandFormWithServices(FormEvent $event): void
    {
        $productOfferTransfer = $event->getData();

        if (!($productOfferTransfer instanceof ProductOfferTransfer)) {
            return;
        }

        $form = $event->getForm();
        $services = $productOfferTransfer->getServices();

        if ($services->count() === 0) {
            return;
        }

        $serviceUuids = [];
        $servicePointId = null;

        foreach ($services as $serviceTransfer) {
            if ($serviceTransfer->getUuid() !== null) {
                $serviceUuids[] = $serviceTransfer->getUuid();
            }

            if ($servicePointId === null && $serviceTransfer->getServicePoint() !== null && $serviceTransfer->getServicePoint()->getIdServicePoint() !== null) {
                $servicePointId = (string)$serviceTransfer->getServicePoint()->getIdServicePoint();
            }
        }

        if ($servicePointId !== null && $form->has(CreateOfferForm::FIELD_SERVICE_POINT)) {
            $form->get(CreateOfferForm::FIELD_SERVICE_POINT)->setData($servicePointId);
        }
        if ($form->has(CreateOfferForm::FIELD_SERVICE_POINT_SERVICES)) {
            $form->get(CreateOfferForm::FIELD_SERVICE_POINT_SERVICES)->setData($serviceUuids);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function expandProductOfferWithServices(FormEvent $event): void
    {
        $productOfferTransfer = $event->getData();

        if (!($productOfferTransfer instanceof ProductOfferTransfer)) {
            return;
        }

        $form = $event->getForm();
        $servicePointServices = $form->get(CreateOfferForm::FIELD_SERVICE_POINT_SERVICES)->getData();

        if (!$servicePointServices || !is_array($servicePointServices)) {
            return;
        }

        $services = new ArrayObject();

        foreach ($servicePointServices as $serviceUuid) {
            $serviceTransfer = new ServiceTransfer();
            $serviceTransfer->setUuid($serviceUuid);

            $services->append($serviceTransfer);
        }

        $productOfferTransfer->setServices($services);
    }
}
