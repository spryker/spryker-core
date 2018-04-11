<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ItemCollectionType;
use Spryker\Zed\ManualOrderEntryGui\Communication\Plugin\Traits\UniqueFlashMessagesTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class ItemManualOrderEntryFormPlugin extends AbstractPlugin implements ManualOrderEntryFormPluginInterface
{
    use UniqueFlashMessagesTrait;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToCartFacadeInterface
     */
    protected $cartFacade;

    /**
     * @var \Spryker\Zed\ManualOrderEntryGui\Dependency\Facade\ManualOrderEntryGuiToMessengerFacadeInterface
     */
    protected $messengerFacade;

    public function __construct()
    {
        $this->cartFacade = $this->getFactory()->getCartFacade();
        $this->messengerFacade = $this->getFactory()->getMessengerFacade();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return ItemCollectionType::TYPE_NAME;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm(Request $request, QuoteTransfer $quoteTransfer): FormInterface
    {
        return $this->getFactory()->createItemsCollectionForm($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function handleData(QuoteTransfer $quoteTransfer, &$form, Request $request): QuoteTransfer
    {
        $items = new ArrayObject();
        $addedSkus = [];

        foreach ($quoteTransfer->getManualOrderEntry()->getItems() as $newItemTransfer) {
            if ($newItemTransfer->getQuantity() <= 0
                || in_array($newItemTransfer->getSku(), $addedSkus)
            ) {
                continue;
            }

            $addedSkus[] = $newItemTransfer->getSku();
            $itemTransfer = new ItemTransfer();
            $itemTransfer->fromArray($newItemTransfer->toArray());

            $items->append($itemTransfer);
        }

        foreach ($quoteTransfer->getItems() as $quoteItemTransfer) {
            $skuAdded = false;
            foreach ($items as $itemTransfer) {
                if ($itemTransfer->getSku() === $quoteItemTransfer->getSku()) {
                    $skuAdded = true;

                    break;
                }
            }

            if (!$skuAdded) {
                $items->append($quoteItemTransfer);
            }
        }

        $quoteTransfer->setItems($items);

        if (count($items)) {
            $quoteTransfer = $this->cartFacade->reloadItems($quoteTransfer);
        }

        $this->updateItems($quoteTransfer);

        $form = $this->createForm($request, $quoteTransfer);
        $form->setData($quoteTransfer->toArray());

        $this->uniqueFlashMessages();

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isFormPreFilled(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getItems()->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function updateItems(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->getManualOrderEntry()->setItems(new ArrayObject());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $newItemTransfer = new ItemTransfer();
            $newItemTransfer->setSku($itemTransfer->getSku())
                ->setQuantity($itemTransfer->getQuantity())
                ->setUnitGrossPrice($itemTransfer->getUnitGrossPrice());

            $quoteTransfer->getManualOrderEntry()->addItems($newItemTransfer);
        }
    }
}
