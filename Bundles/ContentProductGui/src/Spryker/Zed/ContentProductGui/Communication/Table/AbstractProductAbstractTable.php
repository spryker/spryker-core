<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractStore;
use Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToProductImageInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;

abstract class AbstractProductAbstractTable extends AbstractTable
{
    public const HEADER_ID_PRODUCT_ABSTRACT = 'ID';
    public const HEADER_NAME = 'Name';
    public const HEADER_SKU = 'SKU';

    public const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    public const COL_SKU = 'sku';
    public const COL_IMAGE = 'Image';
    public const COL_NAME = 'name';
    public const COL_STORES = 'Stores';
    public const COL_STATUS = 'Status';
    public const COL_SELECTED = 'Selected';
    public const COL_ACTIONS = 'Actions';

    /**
     * @var \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToProductImageInterface $productImageFacade
     */
    protected $productImageFacade;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var string|null
     */
    protected $identifierSuffix;

    /**
     * @var array
     */
    protected $idProductAbstracts;

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractQuery $productQueryContainer
     * @param \Spryker\Zed\ContentProductGui\Dependency\Facade\ContentProductGuiToProductImageInterface $productImageFacade
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string|null $identifierSuffix
     * @param array $idProductAbstracts
     */
    public function __construct(
        SpyProductAbstractQuery $productQueryContainer,
        ContentProductGuiToProductImageInterface $productImageFacade,
        LocaleTransfer $localeTransfer,
        ?string $identifierSuffix,
        array $idProductAbstracts = []
    ) {
        $this->productQueryContainer = $productQueryContainer;
        $this->productImageFacade = $productImageFacade;
        $this->localeTransfer = $localeTransfer;
        $this->identifierSuffix = $identifierSuffix;
        $this->idProductAbstracts = $idProductAbstracts;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstractStore[] $productAbstractStoreEntities
     *
     * @return string
     */
    protected function getStoreNames(array $productAbstractStoreEntities): string
    {
        return array_reduce(
            $productAbstractStoreEntities,
            function (string $accumulator, SpyProductAbstractStore $productAbstractStoreEntity): string {
                return $accumulator . " " . $this->generateLabel($productAbstractStoreEntity->getSpyStore()->getName(), 'label-info');
            },
            ""
        );
    }

    /**
     * @param bool $status
     *
     * @return string
     */
    protected function getStatusLabel(bool $status): string
    {
        if (!$status) {
            return $this->generateLabel('Inactive', 'label-danger');
        }

        return $this->generateLabel('Active', 'label-info');
    }

    /**
     * @param string|null $link
     *
     * @return string
     */
    protected function getProductPreview(?string $link): string
    {
        if ($link) {
            return sprintf('<img src="%s">', $link);
        }

        return '';
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return bool
     */
    protected function getAbstractProductStatus(SpyProductAbstract $productAbstractEntity): bool
    {
        foreach ($productAbstractEntity->getSpyProducts() as $spyProductEntity) {
            if ($spyProductEntity->getIsActive()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string|null
     */
    protected function getProductPreviewUrl(SpyProductAbstract $productAbstractEntity): ?string
    {
        $productImageSetTransferCollection = $this->productImageFacade
            ->getProductImagesSetCollectionByProductAbstractId($productAbstractEntity->getIdProductAbstract());

        foreach ($productImageSetTransferCollection as $productImageSetTransfer) {
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                $previewUrl = $productImageTransfer->getExternalUrlSmall();
                if ($previewUrl) {
                    return $previewUrl;
                }
            }
        }

        return null;
    }
}
