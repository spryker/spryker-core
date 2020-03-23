<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes;
use Propel\Runtime\Collection\ObjectCollection;

class ProductLabelLocalizedAttributesMapper
{
    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\Mapper\LocaleMapper
     */
    private $localeMapper;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\Mapper\LocaleMapper $localeMapper
     */
    public function __construct(LocaleMapper $localeMapper)
    {
        $this->localeMapper = $localeMapper;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes[] $productLabelLocalizedAttributesEntities
     * @param \ArrayObject $productLabelLocalizedAttributesTransferCollection
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer[]
     */
    public function mapProductLabelLocalizedAttributesEntitiesToProductLabelLocalizedAttributesTransferCollection(
        ObjectCollection $productLabelLocalizedAttributesEntities,
        ArrayObject $productLabelLocalizedAttributesTransferCollection
    ): ArrayObject {
        foreach ($productLabelLocalizedAttributesEntities as $productLabelLocalizedAttributesEntity) {
            $productLabelLocalizedAttributesTransferCollection->append(
                $this->mapProductLabelLocalizedAttributesEntityToProductLabelLocalizedAttributesTransfer(
                    $productLabelLocalizedAttributesEntity,
                    new ProductLabelLocalizedAttributesTransfer()
                )
            );
        }

        return $productLabelLocalizedAttributesTransferCollection;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes $productLabelLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer
     */
    public function mapProductLabelLocalizedAttributesEntityToProductLabelLocalizedAttributesTransfer(
        SpyProductLabelLocalizedAttributes $productLabelLocalizedAttributesEntity,
        ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
    ): ProductLabelLocalizedAttributesTransfer {
        $productLabelLocalizedAttributesTransfer->fromArray($productLabelLocalizedAttributesEntity->toArray(), true);

        $productLabelLocalizedAttributesTransfer->setLocale(
            $this->localeMapper->mapLocaleEntityToLocaleTransfer(
                $productLabelLocalizedAttributesEntity->getSpyLocale(),
                new LocaleTransfer()
            )
        );

        return $productLabelLocalizedAttributesTransfer;
    }
}
