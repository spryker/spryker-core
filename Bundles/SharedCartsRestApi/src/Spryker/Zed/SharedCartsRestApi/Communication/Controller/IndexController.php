<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Pyz\Zed\SharedCartsRestApi\Business\SharedCartsRestApiFacade getFacade()
 * @method \Pyz\Zed\SharedCartsRestApi\Communication\SharedCartsRestApiCommunicationFactory getFactory()
 * @method \Pyz\Zed\SharedCartsRestApi\Persistence\SharedCartsRestApiQueryContainer getQueryContainer()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse([
            'test' => 'Greetings!',
        ]);
    }

}
