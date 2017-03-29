<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin\Processor\Pre\Filter\Header;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Dependency\Plugin\ApiPreProcessorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Api\Business\ApiFacade getFacade()
 */
class PaginationByHeaderFilterPreProcessorPlugin extends AbstractPlugin implements ApiPreProcessorPluginInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        return $this->getFactory()
            ->createPreProcessorProvider()
            ->buildPaginationByHeaderFilterPreProcessor()
            ->process($apiRequestTransfer);
    }

}
