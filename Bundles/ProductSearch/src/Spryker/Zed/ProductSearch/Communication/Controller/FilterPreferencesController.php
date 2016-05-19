<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductSearch\Communication\ProductSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSearch\Business\ProductSearchFacade getFacade()
 */
class FilterPreferencesController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse([]);
    }

}
