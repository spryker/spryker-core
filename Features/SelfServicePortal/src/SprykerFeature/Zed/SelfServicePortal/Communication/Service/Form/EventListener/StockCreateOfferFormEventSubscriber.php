<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\EventListener;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\CreateOfferForm;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class StockCreateOfferFormEventSubscriber implements EventSubscriberInterface
{
    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => 'onSubmit',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function onSubmit(FormEvent $event): void
    {
        $productOfferTransfer = $event->getData();

        if (!($productOfferTransfer instanceof ProductOfferTransfer)) {
            return;
        }

        $form = $event->getForm();
        $stockQuantity = $form->get(CreateOfferForm::FIELD_STOCK_QUANTITY)->getData();
        $isNeverOutOfStock = $form->get(CreateOfferForm::FIELD_IS_NEVER_OUT_OF_STOCK)->getData();

        $productOfferStockTransfer = (new ProductOfferStockTransfer())
            ->setQuantity($stockQuantity)
            ->setIsNeverOutOfStock($isNeverOutOfStock);

        $productOfferTransfer->setProductOfferStocks(
            new ArrayObject([$productOfferStockTransfer]),
        );
    }
}
