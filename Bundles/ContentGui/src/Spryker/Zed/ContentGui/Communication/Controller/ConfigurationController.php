<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 */
class ConfigurationController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function contentListJsonAction(): JsonResponse
    {
        $enabledContentTypesForEditor = $this->getFactory()->getConfig()->getEnabledContentTypesForEditor();

        return $this->jsonResponse(
            $this->getFactory()->createContentMapper()->mapEnabledContentTypesForEditor($enabledContentTypesForEditor)
        );
    }
}
