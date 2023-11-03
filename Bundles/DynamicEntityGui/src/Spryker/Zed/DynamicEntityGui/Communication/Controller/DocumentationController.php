<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @method \Spryker\Zed\DynamicEntityGui\Communication\DynamicEntityGuiCommunicationFactory getFactory()
 */
class DocumentationController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadAction(): BinaryFileResponse
    {
        return $this->getFactory()->createSchemaFileResponseBuilder()->createResponse();
    }
}
