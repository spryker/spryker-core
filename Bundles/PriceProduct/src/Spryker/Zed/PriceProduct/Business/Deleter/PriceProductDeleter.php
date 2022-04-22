<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Deleter;

use Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCollectionResponseTransfer;
use Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface;

class PriceProductDeleter implements PriceProductDeleterInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface
     */
    protected $priceProductEntityManager;

    /**
     * @var array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductCollectionDeletePluginInterface>
     */
    protected $priceProductCollectionDeletePlugins;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductEntityManagerInterface $priceProductEntityManager
     * @param array<\Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductCollectionDeletePluginInterface> $priceProductCollectionDeletePlugins
     */
    public function __construct(
        PriceProductEntityManagerInterface $priceProductEntityManager,
        array $priceProductCollectionDeletePlugins
    ) {
        $this->priceProductEntityManager = $priceProductEntityManager;
        $this->priceProductCollectionDeletePlugins = $priceProductCollectionDeletePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCollectionResponseTransfer
     */
    public function deletePriceProductCollection(
        PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
    ): PriceProductCollectionResponseTransfer {
        $priceProductCollectionResponseTransfer = new PriceProductCollectionResponseTransfer();

        $this->priceProductEntityManager->deletePriceProductDefaults($priceProductCollectionDeleteCriteriaTransfer);

        $this->executePriceProductCollectionDeletePlugins(
            $priceProductCollectionDeleteCriteriaTransfer,
        );

        return $priceProductCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    protected function executePriceProductCollectionDeletePlugins(
        PriceProductCollectionDeleteCriteriaTransfer $priceProductCollectionDeleteCriteriaTransfer
    ): void {
        foreach ($this->priceProductCollectionDeletePlugins as $priceProductCollectionDeletePlugin) {
            $priceProductCollectionDeletePlugin->deletePriceProductCollection(
                $priceProductCollectionDeleteCriteriaTransfer,
            );
        }
    }
}
