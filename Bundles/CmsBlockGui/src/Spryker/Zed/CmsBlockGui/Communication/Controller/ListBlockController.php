<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
class ListBlockController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $cmsBlockTable = $this->getFactory()
            ->createCmsBlockTable();

        return $this->viewResponse([
            'blocks' => $cmsBlockTable->render()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createCmsBlockTable();

        return $this->jsonResponse($table->fetchData());
    }

}
