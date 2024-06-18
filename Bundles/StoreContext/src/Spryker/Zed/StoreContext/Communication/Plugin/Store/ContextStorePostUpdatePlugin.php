<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Communication\Plugin\Store;

use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreExtension\Dependency\Plugin\StorePostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\StoreContext\Communication\StoreContextCommunicationFactory getFactory()
 * @method \Spryker\Zed\StoreContext\Business\StoreContextFacadeInterface getFacade()
 * @method \Spryker\Zed\StoreContext\StoreContextConfig getConfig()
 */
class ContextStorePostUpdatePlugin extends AbstractPlugin implements StorePostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Updates store context entity after the store is updated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function execute(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        return $this->getFactory()->createStoreContextMapper()->mapStoreContextCollectionResponseTranferToStoreResponseTransfer(
            $this->getFacade()->updateStoreContextCollection(
                $this->getFactory()->createStoreContextMapper()->mapStoreTranferToStoreContextCollectionRequestTransfer($storeTransfer),
            ),
        );
    }
}
