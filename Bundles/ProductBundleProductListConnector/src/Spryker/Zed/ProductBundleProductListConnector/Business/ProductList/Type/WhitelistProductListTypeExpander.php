<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleProductListConnector\Business\ProductList\Type;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface;
use Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface;

class WhitelistProductListTypeExpander implements ProductListTypeExpanderInterface
{
    protected const MESSAGE_VALUE = 'product_bundle_sku was added to the whitelist with follow products product_bundled_skus.';

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @var \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductBundleFacadeInterface $productBundleFacade
     * @param \Spryker\Zed\ProductBundleProductListConnector\Dependency\Facade\ProductBundleProductListConnectorToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductBundleProductListConnectorToProductBundleFacadeInterface $productBundleFacade,
        ProductBundleProductListConnectorToProductFacadeInterface $productFacade
    ) {
        $this->productBundleFacade = $productBundleFacade;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function expandProductListWithProductBundle(ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        foreach ($productListResponseTransfer->getProductList()->getProductListProductConcreteRelation()->getProductIds() as $idProductConcreteBundle) {
            $productListResponseTransfer = $this->expandByIdProductConcrete($idProductConcreteBundle, $productListResponseTransfer);
        }

        return $productListResponseTransfer;
    }

    /**
     * @param int $idProductConcreteBundle
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandByIdProductConcrete(int $idProductConcreteBundle, ProductListResponseTransfer $productListResponseTransfer): ProductListResponseTransfer
    {
        $productForBundleTransfers = $this->productBundleFacade->findBundledProductsByIdProductConcrete($idProductConcreteBundle);

        if (!$productForBundleTransfers->count()) {
            return $productListResponseTransfer;
        }

        return $this->expandByIdProductConcreteAndProductForBundleTransfers(
            $idProductConcreteBundle,
            $productForBundleTransfers,
            $productListResponseTransfer
        );
    }

    /**
     * @param int $idProductConcreteBundle
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandByIdProductConcreteAndProductForBundleTransfers(
        int $idProductConcreteBundle,
        ArrayObject $productForBundleTransfers,
        ProductListResponseTransfer $productListResponseTransfer
    ): ProductListResponseTransfer {
        $productIdsToAssign = $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->getProductIds();
        $productForBundleIdsToAssign = $this->getProductForBundleIdsToAssign($productForBundleTransfers, $productIdsToAssign);

        if (!$productForBundleIdsToAssign) {
            return $productListResponseTransfer;
        }

        return $this->expandProductListResponseTransfer($productListResponseTransfer, $idProductConcreteBundle, $productForBundleIdsToAssign);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductForBundleTransfer[] $productForBundleTransfers
     * @param int[] $productIdsToAssign
     *
     * @return int[]
     */
    protected function getProductForBundleIdsToAssign(ArrayObject $productForBundleTransfers, array $productIdsToAssign): array
    {
        $productForBundleIdsToAssign = [];

        foreach ($productForBundleTransfers as $productForBundleTransfer) {
            if (in_array($productForBundleTransfer->getIdProductConcrete(), $productIdsToAssign)) {
                continue;
            }

            $productForBundleIdsToAssign[] = $productForBundleTransfer->getIdProductConcrete();
        }

        return $productForBundleIdsToAssign;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductListResponseTransfer $productListResponseTransfer
     * @param int $idProductConcreteBundle
     * @param int[] $productForBundleIdsToAssign
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    protected function expandProductListResponseTransfer(
        ProductListResponseTransfer $productListResponseTransfer,
        int $idProductConcreteBundle,
        array $productForBundleIdsToAssign
    ): ProductListResponseTransfer {
        $messageTransfer = $this->generateMessageTransfer(static::MESSAGE_VALUE, $idProductConcreteBundle, $productForBundleIdsToAssign);
        $productIdsToAssign = $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->getProductIds();

        $productListResponseTransfer->addMessage($messageTransfer);
        $productListResponseTransfer->getProductList()
            ->getProductListProductConcreteRelation()
            ->setProductIds(array_merge($productIdsToAssign, $productForBundleIdsToAssign));

        return $productListResponseTransfer;
    }

    /**
     * @param string $value
     * @param int $idProductConcreteBundle
     * @param int[] $productForBundleIdsToAssign
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function generateMessageTransfer(string $value, int $idProductConcreteBundle, array $productForBundleIdsToAssign): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($value)
            ->setParameters([
                'product_bundle_sku' => $this->getMessageTransferParameter($this->productFacade->getProductConcreteSkusByConcreteIds([$idProductConcreteBundle])),
                'product_bundled_skus' => $this->getMessageTransferParameter($this->productFacade->getProductConcreteSkusByConcreteIds($productForBundleIdsToAssign)),
            ]);
    }

    /**
     * @param string[] $productConcreteSkus
     *
     * @return string
     */
    protected function getMessageTransferParameter(array $productConcreteSkus): string
    {
        return implode(', ', array_keys($productConcreteSkus));
    }
}
