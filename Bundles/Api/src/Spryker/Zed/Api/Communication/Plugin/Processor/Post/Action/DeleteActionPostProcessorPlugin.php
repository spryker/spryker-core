<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin\Processor\Post\Action;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\Dependency\Plugin\ApiPostProcessorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Api\Business\ApiFacade getFacade()
 */
class DeleteActionPostProcessorPlugin extends AbstractPlugin implements ApiPostProcessorPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer)
    {
        return $this->getFactory()
            ->createPostProcessorProvider()
            ->buildDeleteActionPostProcessor()
            ->process($apiRequestTransfer, $apiResponseTransfer);
    }

}
