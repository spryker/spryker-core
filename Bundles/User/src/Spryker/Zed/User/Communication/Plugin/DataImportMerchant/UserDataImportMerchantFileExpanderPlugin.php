<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Communication\Plugin\DataImportMerchant;

use Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer;
use Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\User\Business\UserFacadeInterface getFacade()
 * @method \Spryker\Zed\User\UserConfig getConfig()
 * @method \Spryker\Zed\User\Communication\UserCommunicationFactory getFactory()
 * @method \Spryker\Zed\User\Business\UserBusinessFactory getBusinessFactory()
 */
class UserDataImportMerchantFileExpanderPlugin extends AbstractPlugin implements DataImportMerchantFileExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `DataImportMerchantFileTransfer.idUser` to be set.
     * - Expands `DataImportMerchantFile` transfers with `User` data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\DataImportMerchantFileCollectionTransfer
     */
    public function expand(
        DataImportMerchantFileCollectionTransfer $dataImportMerchantFileCollectionTransfer
    ): DataImportMerchantFileCollectionTransfer {
        return $this->getBusinessFactory()
            ->createUserDataImportMerchantFileExpander()
            ->expand($dataImportMerchantFileCollectionTransfer);
    }
}
