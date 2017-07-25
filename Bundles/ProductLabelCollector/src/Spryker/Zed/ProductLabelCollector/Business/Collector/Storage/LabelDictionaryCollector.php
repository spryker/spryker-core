<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelCollector\Business\Collector\Storage;

use DateTime;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\StorageProductLabelTransfer;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\ProductLabel\ProductLabelConstants;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\ProductLabelCollector\Dependency\Facade\ProductLabelCollectorToProductLabelInterface;

class LabelDictionaryCollector extends AbstractStoragePropelCollector
{

    /**
     * @var \Spryker\Zed\ProductLabelCollector\Dependency\Facade\ProductLabelCollectorToProductLabelInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\ProductLabelCollector\Dependency\Facade\ProductLabelCollectorToProductLabelInterface $productLabelFacade
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        ProductLabelCollectorToProductLabelInterface $productLabelFacade
    ) {
        parent::__construct($utilDataReaderService);

        $this->productLabelFacade = $productLabelFacade;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $dictionary = [];

        foreach ($this->productLabelFacade->findAllLabels() as $productLabelTransfer) {
            if (!$this->shouldExportProductLabel($productLabelTransfer)) {
                continue;
            }

            $storageProductLabelTransfer = new StorageProductLabelTransfer();
            $storageProductLabelTransfer->fromArray($productLabelTransfer->toArray(), true);
            $storageProductLabelTransfer
                ->setKey($productLabelTransfer->getName())
                ->setName($this->getNameAttributeForCurrentLocale($productLabelTransfer));

            $dictionary[] = $storageProductLabelTransfer->toArray();
        }

        return $dictionary;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return bool
     */
    protected function shouldExportProductLabel(ProductLabelTransfer $productLabelTransfer)
    {
        if (!$productLabelTransfer->getIsActive()) {
            return false;
        }

        if (!$this->isValidByDate($productLabelTransfer)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return bool
     */
    protected function isValidByDate(ProductLabelTransfer $productLabelTransfer)
    {
        $isValidFromDate = $this->isValidByDateFrom($productLabelTransfer);
        $isValidToDate = $this->isValidByDateTo($productLabelTransfer);

        return ($isValidFromDate && $isValidToDate);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return bool
     */
    protected function isValidByDateFrom(ProductLabelTransfer $productLabelTransfer)
    {
        if (!$productLabelTransfer->getValidFrom()) {
            return true;
        }

        $validFromDate = new DateTime($productLabelTransfer->getValidFrom());
        $now = new DateTime();

        if ($now < $validFromDate) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return bool
     */
    protected function isValidByDateTo(ProductLabelTransfer $productLabelTransfer)
    {
        if (!$productLabelTransfer->getValidTo()) {
            return true;
        }

        $validToDate = new DateTime($productLabelTransfer->getValidTo());
        $now = new DateTime();

        if ($validToDate < $now) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return string
     */
    protected function getNameAttributeForCurrentLocale(ProductLabelTransfer $productLabelTransfer)
    {
        foreach ($productLabelTransfer->getLocalizedAttributesCollection() as $attributesTransfer) {
            if ($attributesTransfer->getFkLocale() === $this->locale->getIdLocale()) {
                return $attributesTransfer->getName();
            }
        }

        return $productLabelTransfer->getName();
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductLabelConstants::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY;
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }

}
