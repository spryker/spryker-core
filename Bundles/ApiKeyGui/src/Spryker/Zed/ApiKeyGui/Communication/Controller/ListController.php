<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\ApiKeyGui\Communication\ApiKeyGuiCommunicationFactory getFactory()
 */
class ListController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $apiKeyTable = $this->getFactory()->createApiKeyTable();

        return $this->viewResponse([
            'apiKeyTable' => $apiKeyTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $apiKeyTable = $this->getFactory()->createApiKeyTable();

        return $this->jsonResponse(
            $apiKeyTable->fetchData(),
        );
    }
}
