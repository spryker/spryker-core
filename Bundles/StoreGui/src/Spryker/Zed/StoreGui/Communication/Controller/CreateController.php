<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\StoreGui\StoreGuiConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\StoreGui\Communication\StoreGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_STORE_CREATED = 'Store created successfully';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createStoreFormDataProvider();
        $storeTransfer = $dataProvider->getData();
        $storeForm = $this->getFactory()
            ->getCreateStoreForm(
                $storeTransfer,
                $dataProvider->getOptions(),
            )
            ->handleRequest($request);

        if ($storeForm->isSubmitted() && $storeForm->isValid()) {
            return $this->createStore($storeForm);
        }

        return $this->viewResponse([
            'form' => $storeForm->createView(),
            'storeFormTabs' => $this->getFactory()->createStoreFormTabs()->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $storeForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function createStore(FormInterface $storeForm)
    {
        $storeTransfer = $storeForm->getData();
        $storeResponseTransfer = $this->getFactory()
            ->getStoreFacade()
            ->createStore($storeTransfer);

        if ($storeResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_STORE_CREATED);

            return $this->redirectResponse(StoreGuiConfig::URL_STORE_LIST);
        }

        foreach ($storeResponseTransfer->getMessages() as $storeErrorTransfer) {
            $this->addErrorMessage($storeErrorTransfer->getValueOrFail());
        }

        return $this->viewResponse([
            'form' => $storeForm->createView(),
            'storeFormTabs' => $this->getFactory()->createStoreFormTabs()->createView(),
        ]);
    }
}
