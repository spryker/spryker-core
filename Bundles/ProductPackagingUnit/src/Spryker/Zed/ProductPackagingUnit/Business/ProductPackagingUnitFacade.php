<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ProductPackagingLeadProductTransfer;
use Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductPackagingUnit\Business\ProductPackagingUnitBusinessFactory getFactory()
 */
class ProductPackagingUnitFacade extends AbstractFacade implements ProductPackagingUnitFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function installProductPackagingUnitTypes(): void
    {
        $this->getFactory()
            ->createProductPackagingUnitTypeInstaller()
            ->install();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getInfrastructuralPackagingUnitTypeNames(): array
    {
        return $this->getFactory()
            ->getConfig()
            ->getInfrastructuralPackagingUnitTypeNames();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getDefaultPackagingUnitTypeName(): string
    {
        return $this->getFactory()->getConfig()->getDefaultPackagingUnitTypeName();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getProductPackagingUnitTypeByName(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        return $this->getFactory()
            ->createProductPackagingUnitTypeReader()
            ->getProductPackagingUnitTypeByName($productPackagingUnitTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function getProductPackagingUnitTypeById(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        return $this->getFactory()
            ->createProductPackagingUnitTypeReader()
            ->getProductPackagingUnitTypeById($productPackagingUnitTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return int
     */
    public function countProductPackagingUnitsByTypeId(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): int {
        return $this->getFactory()
            ->createProductPackagingUnitTypeReader()
            ->countProductPackagingUnitsByTypeId($productPackagingUnitTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductPackagingLeadProductTransfer|null
     */
    public function getProductPackagingLeadProductByIdProductAbstract(
        int $idProductAbstract
    ): ?ProductPackagingLeadProductTransfer {
        return $this->getFactory()
            ->createProductPackagingUnitLeadProductReader()
            ->getProductPackagingLeadProductByIdProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function createProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        return $this->getFactory()
            ->createProductPackagingUnitTypeWriter()
            ->createProductPackagingUnitType($productPackagingUnitTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer
     */
    public function updateProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): ProductPackagingUnitTypeTransfer {
        return $this->getFactory()
            ->createProductPackagingUnitTypeWriter()
            ->updateProductPackagingUnitType($productPackagingUnitTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
     *
     * @return bool
     */
    public function deleteProductPackagingUnitType(
        ProductPackagingUnitTypeTransfer $productPackagingUnitTypeTransfer
    ): bool {
        return $this->getFactory()
            ->createProductPackagingUnitTypeWriter()
            ->deleteProductPackagingUnitType($productPackagingUnitTypeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productPackagingUnitTypeIds
     *
     * @return array
     */
    public function getIdProductAbstractsByIdProductPackagingUnitTypes(array $productPackagingUnitTypeIds): array
    {
        return $this->getFactory()
            ->createProductPackagingUnitTypeReader()
            ->getIdProductAbstractsByIdProductPackagingUnitTypes($productPackagingUnitTypeIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithQuantityPackagingUnit(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        return $this->getFactory()
            ->createCartChangeExpander()
            ->expandWithQuantityPackagingUnit($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function preCheckCartAvailability(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        return $this->getFactory()
            ->createProductPackagingUnitCartPreCheck()
            ->checkCartAvailability($cartChangeTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     *
     * @return void
     */
    public function updateProductPackagingUnitLeadProductAvailability(string $sku): void
    {
        $this->getFactory()
            ->createProductPackagingUnitAvailabilityHandler()
            ->updateProductPackagingUnitLeadProductAvailability($sku);
    }
}
