<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Queue\Communication\QueueCommunicationFactory getFactory()
 * @method \Spryker\Zed\Queue\Persistence\QueueQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Queue\Business\QueueFacadeInterface getFacade()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $queueProcessTable = $this->getFactory()
            ->createQueueProcessTable();

        return [
            'queueProcesses' => $queueProcessTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createQueueProcessTable();

        return $this->jsonResponse($table->fetchData());
    }
}
