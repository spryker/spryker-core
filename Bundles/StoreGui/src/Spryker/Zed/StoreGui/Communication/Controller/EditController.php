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
class EditController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_ID_STORE = 'id-store';

    /**
     * @var string
     */
    protected const MESSAGE_STORE_UPDATED = 'Store updated successfully';

    /**
     * @var string
     */
    protected const MESSAGE_ERROR_STORE_NOT_EXIST = 'Store with id `%s` does not exist';

    /**
     * @var string
     */
    protected const MESSAGE_ID_PLACEHOLDER = '%s';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idStore = $this->castId($request->get(static::REQUEST_ID_STORE));

        $dataProvider = $this->getFactory()->createStoreFormDataProvider();
        $storeTransfer = $dataProvider->getData($idStore);

        if (!$storeTransfer->getIdStore()) {
            $this->addErrorMessage(static::MESSAGE_ERROR_STORE_NOT_EXIST, [
                static::MESSAGE_ID_PLACEHOLDER => $idStore,
            ]);

            return $this->redirectResponse(StoreGuiConfig::URL_STORE_LIST);
        }

        $updateStoreForm = $this->getFactory()
            ->getUpdateStoreForm(
                $storeTransfer,
                $dataProvider->getOptions(),
            )
            ->handleRequest($request);

        if ($updateStoreForm->isSubmitted() && $updateStoreForm->isValid()) {
            return $this->updateStore($updateStoreForm);
        }

        return $this->viewResponse([
            'form' => $updateStoreForm->createView(),
            'storeFormTabs' => $this->getFactory()->createStoreFormTabs()->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $storeForm
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function updateStore(FormInterface $storeForm)
    {
        $storeTransfer = $storeForm->getData();
        $storeResponseTransfer = $this->getFactory()
            ->getStoreFacade()
            ->updateStore($storeTransfer);

        if ($storeResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_STORE_UPDATED);

            return $this->redirectResponse(StoreGuiConfig::URL_STORE_LIST);
        }

        foreach ($storeResponseTransfer->getMessages() as $storeErrorTransfer) {
            $this->addErrorMessage($storeErrorTransfer->getValueOrFail(), $storeErrorTransfer->getParameters());
        }

        return $this->viewResponse([
            'form' => $this->getFactory()
                ->getUpdateStoreForm(
                    $storeTransfer,
                    $this->getFactory()->createStoreFormDataProvider()->getOptions(),
                )->createView(),
            'storeFormTabs' => $this->getFactory()->createStoreFormTabs()->createView(),
        ]);
    }
}
