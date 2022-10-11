<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AppCatalogGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\AppCatalogGui\Communication\AppCatalogGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\AppCatalogGui\Business\AppCatalogGuiFacadeInterface getFacade()
 */
class IndexController extends AbstractController
{
    /**
     * @return array<string, mixed>
     */
    public function indexAction(): array
    {
        $localeTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        $storeTransfer = $this->getFactory()->getStoreFacade()->getCurrentStore();

        return $this->viewResponse([
            'localeName' => mb_substr($localeTransfer->getLocaleNameOrFail(), 0, 2),
            'storeReference' => $storeTransfer->getStoreReference() ?? '',
            'appCatalogScriptUrl' => $this->getFactory()->getConfig()->getAppCatalogScriptUrl(),
        ]);
    }
}
