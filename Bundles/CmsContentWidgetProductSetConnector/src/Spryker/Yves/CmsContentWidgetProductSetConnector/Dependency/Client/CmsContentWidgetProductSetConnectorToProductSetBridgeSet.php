<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSetConnector\Dependency\Client;

class CmsContentWidgetProductSetConnectorToProductSetBridgeSet implements CmsContentWidgetProductSetConnectorToProductSetInterface
{
    /**
     * @var \Spryker\Client\ProductSet\ProductSetClientInterface
     */
    protected $productSetClient;

    /**
     * @param \Spryker\Client\ProductSet\ProductSetClientInterface $productSetClient
     */
    public function __construct($productSetClient)
    {
        $this->productSetClient = $productSetClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer|null
     */
    public function findProductSetByIdProductSet($idProductAbstract)
    {
        return $this->productSetClient->findProductSetByIdProductSet($idProductAbstract);
    }
}
