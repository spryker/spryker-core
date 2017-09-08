<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Controller;

use Exception;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\GiftCard\Communication\GiftCardCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{

    /**
     * @throws \Exception
     *
     * @return array
     */
    public function indexAction()
    {
        //TODO implement
        //aggregate infos like balance, usage, active state and code here
        //implementing the balance info will cover the replacement strategy as well
        throw new Exception('not implemented');
    }

    /**
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        //TODO implement
        throw new Exception('not implemented');
    }

}
