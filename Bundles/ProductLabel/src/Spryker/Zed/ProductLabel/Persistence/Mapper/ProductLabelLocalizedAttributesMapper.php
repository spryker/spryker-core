<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\SpyProductLabelEntityTransfer;
use Generated\Shared\Transfer\SpyProductLabelLocalizedAttributesEntityTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes;
use Propel\Runtime\Collection\ObjectCollection;

class ProductLabelLocalizedAttributesMapper
{
    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\Mapper\LocaleMapper
     */
    protected $localeMapper;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\Mapper\LocaleMapper $localeMapper
     */
    public function __construct(LocaleMapper $localeMapper)
    {
        $this->localeMapper = $localeMapper;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes> $productLabelLocalizedAttributesEntities
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer> $productLabelLocalizedAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer>
     */
    public function mapProductLabelLocalizedAttributesEntitiesToProductLabelLocalizedAttributesTransfers(
        ObjectCollection $productLabelLocalizedAttributesEntities,
        ArrayObject $productLabelLocalizedAttributesTransfers
    ): ArrayObject {
        foreach ($productLabelLocalizedAttributesEntities as $productLabelLocalizedAttributesEntity) {
            $productLabelLocalizedAttributesTransfers->append($this->mapProductLabelLocalizedAttributesEntityToProductLabelLocalizedAttributesTransfer(
                $productLabelLocalizedAttributesEntity,
                new ProductLabelLocalizedAttributesTransfer(),
            ));
        }

        return $productLabelLocalizedAttributesTransfers;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes $productLabelLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer
     */
    protected function mapProductLabelLocalizedAttributesEntityToProductLabelLocalizedAttributesTransfer(
        SpyProductLabelLocalizedAttributes $productLabelLocalizedAttributesEntity,
        ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
    ): ProductLabelLocalizedAttributesTransfer {
        return $productLabelLocalizedAttributesTransfer->fromArray($productLabelLocalizedAttributesEntity->toArray(), true)
            ->setLocale($this->localeMapper->mapLocaleEntityToLocaleTransfer($productLabelLocalizedAttributesEntity->getSpyLocale(), new LocaleTransfer()))
            ->setProductLabel($this->mapProductLabelEntityToProductLabelTransfer($productLabelLocalizedAttributesEntity->getSpyProductLabel(), new ProductLabelTransfer()));
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function mapProductLabelEntityToProductLabelTransfer(
        SpyProductLabel $productLabelEntity,
        ProductLabelTransfer $productLabelTransfer
    ): ProductLabelTransfer {
        return $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SpyProductLabelLocalizedAttributesEntityTransfer> $productLabelLocalizedAttributesEntityTransfers
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer> $productLabelLocalizedAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer>
     */
    public function mapProductLabelLocalizedAttributesEntityTransfersToProductLabelLocalizedAttributesTransfers(
        ArrayObject $productLabelLocalizedAttributesEntityTransfers,
        ArrayObject $productLabelLocalizedAttributesTransfers
    ): ArrayObject {
        foreach ($productLabelLocalizedAttributesEntityTransfers as $productLabelLocalizedAttributesEntityTransfer) {
            $productLabelLocalizedAttributesTransfers->append($this->mapProductLabelLocalizedAttributesEntityTransferToProductLabelLocalizedAttributesTransfer(
                $productLabelLocalizedAttributesEntityTransfer,
                new ProductLabelLocalizedAttributesTransfer(),
            ));
        }

        return $productLabelLocalizedAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductLabelLocalizedAttributesEntityTransfer $productLabelLocalizedAttributesEntityTransfer
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer
     */
    protected function mapProductLabelLocalizedAttributesEntityTransferToProductLabelLocalizedAttributesTransfer(
        SpyProductLabelLocalizedAttributesEntityTransfer $productLabelLocalizedAttributesEntityTransfer,
        ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
    ): ProductLabelLocalizedAttributesTransfer {
        $productLabelLocalizedAttributesTransfer = $productLabelLocalizedAttributesTransfer->fromArray($productLabelLocalizedAttributesEntityTransfer->toArray(), true)
            ->setLocale($this->localeMapper->mapLocaleEntityTransaferToLocaleTransfer($productLabelLocalizedAttributesEntityTransfer->getSpyLocale(), new LocaleTransfer()));

        if ($productLabelLocalizedAttributesEntityTransfer->getSpyProductLabel()) {
            $productLabelLocalizedAttributesTransfer
                ->setProductLabel($this->mapProductLabelEntityTransferToProductLabelTransfer($productLabelLocalizedAttributesEntityTransfer->getSpyProductLabel(), new ProductLabelTransfer()));
        }

        return $productLabelLocalizedAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductLabelEntityTransfer $productLabelEntityTransfer
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function mapProductLabelEntityTransferToProductLabelTransfer(
        SpyProductLabelEntityTransfer $productLabelEntityTransfer,
        ProductLabelTransfer $productLabelTransfer
    ): ProductLabelTransfer {
        return $productLabelTransfer->fromArray($productLabelEntityTransfer->toArray(), true);
    }
}
