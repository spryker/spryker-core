<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Controller;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\StoreGui\StoreGuiConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\StoreGui\Communication\StoreGuiCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_ID_STORE = 'id-store';

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
        $idStore = $request->get(static::REQUEST_ID_STORE);

        if (!$idStore) {
            return $this->redirectResponse(StoreGuiConfig::URL_STORE_LIST);
        }

        $idStore = $this->castId($idStore);
        $dataProvider = $this->getFactory()->createStoreFormDataProvider();
        $storeTransfer = $dataProvider->getData($idStore);

        if ($storeTransfer->getIdStore() === null) {
            $this->addErrorMessage(static::MESSAGE_ERROR_STORE_NOT_EXIST, [
                static::MESSAGE_ID_PLACEHOLDER => $idStore,
            ]);

            return $this->redirectResponse(StoreGuiConfig::URL_STORE_LIST);
        }

        return $this->viewResponse([
            'store' => $storeTransfer,
            'additional_blocks' => $this->getAdditionalBlocks($storeTransfer),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<string, array<mixed>>
     */
    protected function getAdditionalBlocks(StoreTransfer $storeTransfer): array
    {
        $infoBlocks = [];

        foreach ($this->getFactory()->getStoreViewExpanderPlugins() as $formViewExpanderPlugin) {
            $infoBlocks[$formViewExpanderPlugin->getTemplatePath()] = $formViewExpanderPlugin->getTemplateData($storeTransfer);
        }

        return $infoBlocks;
    }
}
