<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Expander;

use Generated\Shared\Transfer\MerchantCollectionTransfer;

class MerchantExpander implements MerchantExpanderInterface
{
    /**
     * @deprecated Use {@link \Spryker\Zed\Merchant\Business\Expander\MerchantExpander::$merchantBulkExpanderPlugins} instead.
     *
     * @var list<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface>
     */
    protected array $merchantExpanderPlugins;

    /**
     * @var list<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantBulkExpanderPluginInterface>
     */
    protected array $merchantBulkExpanderPlugins;

    /**
     * @param list<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantExpanderPluginInterface> $merchantExpanderPlugins
     * @param list<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantBulkExpanderPluginInterface> $merchantBulkExpanderPlugins
     */
    public function __construct(
        array $merchantExpanderPlugins,
        array $merchantBulkExpanderPlugins
    ) {
        $this->merchantExpanderPlugins = $merchantExpanderPlugins;
        $this->merchantBulkExpanderPlugins = $merchantBulkExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function expand(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer
    {
        $merchantCollectionTransfer = $this->executeMerchantSingleExpanderPlugins($merchantCollectionTransfer);
        $merchantCollectionTransfer = $this->executeMerchantBulkExpanderPlugins($merchantCollectionTransfer);

        return $merchantCollectionTransfer;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Merchant\Business\Expander\MerchantExpander::executeMerchantBulkExpanderPlugins()} instead.
     *
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    protected function executeMerchantSingleExpanderPlugins(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer
    {
        $resultMerchantCollectionTransfer = new MerchantCollectionTransfer();

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            foreach ($this->merchantExpanderPlugins as $merchantExpanderPlugin) {
                $merchantTransfer = $merchantExpanderPlugin->expand($merchantTransfer);
            }

            $resultMerchantCollectionTransfer->addMerchants($merchantTransfer);
        }

        return $resultMerchantCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    protected function executeMerchantBulkExpanderPlugins(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer
    {
        foreach ($this->merchantBulkExpanderPlugins as $merchantBulkExpanderPlugin) {
            $merchantCollectionTransfer = $merchantBulkExpanderPlugin->expand($merchantCollectionTransfer);
        }

        return $merchantCollectionTransfer;
    }
}
