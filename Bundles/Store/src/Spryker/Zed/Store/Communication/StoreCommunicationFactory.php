<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Store\Communication\Form\Transformer\IdStoresDataTransformer;

/**
 * @method \Spryker\Zed\Store\StoreConfig getConfig()
 * @method \Spryker\Zed\Store\Persistence\StoreQueryContainerInterface getQueryContainer()
 */
class StoreCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Symfony\Component\Form\DataTransformerInterface
     */
    public function createIdStoresDataTransformer()
    {
        return new IdStoresDataTransformer();
    }
}
