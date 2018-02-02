<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Dataset\Business\DatasetFacade getFacade()
 * @method \Spryker\Zed\Dataset\Communication\DatasetCommunicationFactory getFactory()
 * @method \Spryker\Zed\Dataset\Persistence\DatasetQueryContainer getQueryContainer()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $fileTable = $this->getFactory()
            ->createDatasetTable();

        return [
            'datasets' => $fileTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createDatasetTable();

        return $this->jsonResponse($table->fetchData());
    }
}
