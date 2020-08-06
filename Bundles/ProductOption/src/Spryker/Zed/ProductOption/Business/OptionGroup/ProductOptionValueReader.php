<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use Generated\Shared\Transfer\ProductOptionCollectionTransfer;
use Generated\Shared\Transfer\ProductOptionCriteriaTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionNotFoundException;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionValueReader implements ProductOptionValueReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceReaderInterface
     */
    protected $productOptionValuePriceReader;

    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceReaderInterface $productOptionValuePriceReader
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     */
    public function __construct(
        ProductOptionValuePriceReaderInterface $productOptionValuePriceReader,
        ProductOptionQueryContainerInterface $productOptionQueryContainer
    ) {
        $this->productOptionValuePriceReader = $productOptionValuePriceReader;
        $this->productOptionQueryContainer = $productOptionQueryContainer;
    }

    /**
     * @param int $idProductOptionValue
     * @param string|null $currencyCode
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\ProductOptionNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOption($idProductOptionValue, ?string $currencyCode = null)
    {
        $productOptionValueEntity = $this->findOptionValueById((int)$idProductOptionValue);

        if ($productOptionValueEntity) {
            return $this->hydrateProductOptionTransfer($productOptionValueEntity, $currencyCode);
        }

        throw new ProductOptionNotFoundException(
            sprintf('Product option with id "%d" not found in persistence.', $idProductOptionValue)
        );
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer|null
     */
    public function findProductOptionByIdProductOptionValue(int $idProductOptionValue): ?ProductOptionTransfer
    {
        $productOptionValueEntity = $this->findOptionValueById($idProductOptionValue);

        if ($productOptionValueEntity) {
            return $this->hydrateProductOptionTransfer($productOptionValueEntity);
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionCollectionTransfer
     */
    public function getProductOptionCollectionByProductOptionCriteria(
        ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
    ): ProductOptionCollectionTransfer {
        $productOptionValueEntities = $this->productOptionQueryContainer
            ->queryProductOptionByProductOptionCriteria($productOptionCriteriaTransfer)
            ->find();

        return $this->hydrateProductOptionCollectionTransfer(
            new ProductOptionCollectionTransfer(),
            $productOptionValueEntities->getArrayCopy(),
            $productOptionCriteriaTransfer
        );
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return bool
     */
    public function checkProductOptionValueExistence(int $idProductOptionValue): bool
    {
        return $this->productOptionQueryContainer
            ->queryProductOptionByValueId($idProductOptionValue)
            ->exists();
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $productOptionValueEntity
     * @param string|null $currencyCode
     * @param string|null $priceMode
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    protected function hydrateProductOptionTransfer(
        SpyProductOptionValue $productOptionValueEntity,
        ?string $currencyCode = null,
        ?string $priceMode = null
    ): ProductOptionTransfer {
        $productOptionTransfer = new ProductOptionTransfer();
        $productOptionTransfer->fromArray($productOptionValueEntity->toArray(), true);
        $productOptionTransfer->setGroupName($productOptionValueEntity->getSpyProductOptionGroup()->getName());
        $productOptionTransfer->setUnitGrossPrice($this->productOptionValuePriceReader->getCurrentGrossPrice($productOptionValueEntity, $currencyCode));
        $productOptionTransfer->setUnitNetPrice($this->productOptionValuePriceReader->getCurrentNetPrice($productOptionValueEntity, $currencyCode));
        $productOptionTransfer->setUnitPrice($this->productOptionValuePriceReader->resolveUnitPrice($productOptionTransfer, $priceMode));

        return $productOptionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionCollectionTransfer $productOptionCollectionTransfer
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue[] $productOptionValueEntities
     * @param \Generated\Shared\Transfer\ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionCollectionTransfer
     */
    protected function hydrateProductOptionCollectionTransfer(
        ProductOptionCollectionTransfer $productOptionCollectionTransfer,
        array $productOptionValueEntities,
        ProductOptionCriteriaTransfer $productOptionCriteriaTransfer
    ): ProductOptionCollectionTransfer {
        foreach ($productOptionValueEntities as $productOptionValueEntity) {
            $productOptionCollectionTransfer->addProductOption(
                $this->hydrateProductOptionTransfer(
                    $productOptionValueEntity,
                    $productOptionCriteriaTransfer->getCurrencyIsoCode(),
                    $productOptionCriteriaTransfer->getPriceMode()
                )
            );
        }

        return $productOptionCollectionTransfer;
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue|null
     */
    protected function findOptionValueById(int $idProductOptionValue): ?SpyProductOptionValue
    {
        $productOptionValueEntity = $this->productOptionQueryContainer
            ->queryProductOptionByValueId($idProductOptionValue)
            ->findOne();

        return $productOptionValueEntity;
    }
}
