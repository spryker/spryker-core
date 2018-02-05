<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageBusinessFactory getFactory()
 */
class ProductLabelStorageFacade extends AbstractFacade implements ProductLabelStorageFacadeInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function publishLabelDictionary()
    {
        $this->getFactory()->createProductLabelDictionaryStorageWriter()->publish();
    }

    /**
     * @api
     *
     * @return void
     */
    public function unpublishLabelDictionary()
    {
        $this->getFactory()->createProductLabelDictionaryStorageWriter()->unpublish();
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publishProductLabel(array $productAbstractIds)
    {
        $this->getFactory()->createProductLabelStorageWriter()->publish($productAbstractIds);
    }

    /**
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function unpublishProductLabel(array $productAbstractIds)
    {
        $this->getFactory()->createProductLabelStorageWriter()->unpublish($productAbstractIds);
    }
}
