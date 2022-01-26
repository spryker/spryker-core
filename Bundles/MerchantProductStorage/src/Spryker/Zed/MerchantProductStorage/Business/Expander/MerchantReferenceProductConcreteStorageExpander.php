<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage\Business\Expander;

use Generated\Shared\Transfer\MerchantProductCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToMerchantProductFacadeInterface;

class MerchantReferenceProductConcreteStorageExpander implements MerchantReferenceProductConcreteStorageExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToMerchantProductFacadeInterface
     */
    protected $merchantProductFacade;

    /**
     * @param \Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToMerchantProductFacadeInterface $merchantProductFacade
     */
    public function __construct(
        MerchantProductStorageToMerchantProductFacadeInterface $merchantProductFacade
    ) {
        $this->merchantProductFacade = $merchantProductFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    public function expandProductConcreteStorages(array $productConcreteStorageTransfers): array
    {
        $merchantProductCriteriaTransfer = new MerchantProductCriteriaTransfer();
        foreach ($productConcreteStorageTransfers as $productConcreteStorageTransfer) {
            $merchantProductCriteriaTransfer->addIdProductAbstract($productConcreteStorageTransfer->getIdProductAbstract());
        }

        $merchantProductCollectionTransfer = $this->merchantProductFacade->get($merchantProductCriteriaTransfer);
        $merchantReferencesIndexedByIdProductAbstract = $this->getMerchantReferencesIndexedByIdProductAbstract($merchantProductCollectionTransfer);

        foreach ($productConcreteStorageTransfers as $productConcreteStorageTransfer) {
            if (
                !$productConcreteStorageTransfer->getMerchantReference() &&
                isset($merchantReferencesIndexedByIdProductAbstract[$productConcreteStorageTransfer->getIdProductAbstract()])
            ) {
                $productConcreteStorageTransfer->setMerchantReference(
                    $merchantReferencesIndexedByIdProductAbstract[$productConcreteStorageTransfer->getIdProductAbstract()],
                );
            }
        }

        return $productConcreteStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCollectionTransfer $merchantProductCollectionTransfer
     *
     * @return array<int|string, string|null>
     */
    protected function getMerchantReferencesIndexedByIdProductAbstract(
        MerchantProductCollectionTransfer $merchantProductCollectionTransfer
    ): array {
        $merchantReferencesIndexedByIdProductAbstract = [];

        foreach ($merchantProductCollectionTransfer->getMerchantProducts() as $merchantProduct) {
            $merchantReferencesIndexedByIdProductAbstract[$merchantProduct->getIdProductAbstract()] = $merchantProduct->getMerchantReference();
        }

        return $merchantReferencesIndexedByIdProductAbstract;
    }
}
