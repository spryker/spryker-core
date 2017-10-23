<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Communication\Controller\Fixtures;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

class MockController extends AbstractController
{
    /**
     * @param mixed $id
     *
     * @return int
     */
    public function indexAction($id)
    {
        return $this->castId($id);
    }
}
