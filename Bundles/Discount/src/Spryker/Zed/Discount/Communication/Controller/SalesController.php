<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Discount\Business\DiscountFacadeInterface getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function listAction(Request $request)
    {
        return [
            'order' => $request->request->get('orderTransfer'),
        ];
    }
}
