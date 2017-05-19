<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

class ViewController extends AbstractController
{

    const PARAM_ID = 'id';

    public function indexAction()
    {
        return $this->viewResponse([]);
    }

}
