<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

use Generated\Shared\Transfer\MerchantTransfer;

class MerchantPluginExecutor implements MerchantPluginExecutorInterface
{
    /**
     * @var array|\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostSavePluginInterface[]
     */
    protected $merchantPostSavePlugins;

    /**
     * @var array|\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantHydrationPluginInterface[]
     */
    protected $merchantHydratePlugins;

    /**
     * @param \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostSavePluginInterface[] $merchantPostSavePlugins
     * @param \Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantHydrationPluginInterface[] $merchantHydratePlugins
     */
    public function __construct(array $merchantPostSavePlugins, array $merchantHydratePlugins)
    {
        $this->merchantPostSavePlugins = $merchantPostSavePlugins;
        $this->merchantHydratePlugins = $merchantHydratePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function executeMerchantPostSavePlugins(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        foreach ($this->merchantPostSavePlugins as $merchantPostSavePlugin) {
            $merchantTransfer = $merchantPostSavePlugin->postSave($merchantTransfer);
        }

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function executeMerchantHydratePlugins(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        foreach ($this->merchantHydratePlugins as $merchantHydratePlugin) {
            $merchantTransfer = $merchantHydratePlugin->hydrate($merchantTransfer);
        }

        return $merchantTransfer;
    }
}
