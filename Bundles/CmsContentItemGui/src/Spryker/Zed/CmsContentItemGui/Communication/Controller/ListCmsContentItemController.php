<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentItemGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\CmsContentItemGui\Communication\CmsContentItemGuiCommunicationFactory getFactory()
 */
class ListCmsContentItemController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction(): array
    {
        $cmsContentItemTable = $this->getFactory()->createCmsContentItemTable();

        return $this->viewResponse([
            'cmsContentItems' => $cmsContentItemTable->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $cmsContentItemTable = $this->getFactory()->createCmsContentItemTable();

        return $this->jsonResponse($cmsContentItemTable->fetchData());
    }
}
