<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelCollector\Business\Collector\Storage;

use DateTime;
use Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\ProductLabel\ProductLabelConfig;
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

        foreach ($this->productLabelFacade->readAllLabels() as $productLabelTransfer) {
            if (!$this->shouldExportProductLabel($productLabelTransfer)) {
                continue;
            }

            $projectionTransfer = new ProductLabelStorageProjectionTransfer();
            $projectionTransfer->fromArray($productLabelTransfer->toArray(), true);
            $projectionTransfer->setName($this->getNameAttributeForCurrentLocale($productLabelTransfer));

            $dictionary[] = $projectionTransfer->toArray();
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
        $now = new DateTime();
        $isValidFromDate = $this->isValidByDateFrom($productLabelTransfer, $now);
        $isValidToDate = $this->isValidByDateTo($productLabelTransfer, $now);

        return ($isValidFromDate && $isValidToDate);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param \DateTime $now
     *
     * @return bool
     */
    protected function isValidByDateFrom(ProductLabelTransfer $productLabelTransfer, DateTime $now)
    {
        if (!$productLabelTransfer->getValidFrom()) {
            return true;
        }

        /** @var \DateTime $validFromDate */
        $validFromDate = $productLabelTransfer->getValidFrom();

        if ($now->getTimestamp() < $validFromDate->getTimestamp()) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param \DateTime $now
     *
     * @return bool
     */
    protected function isValidByDateTo(ProductLabelTransfer $productLabelTransfer, DateTime $now)
    {
        if (!$productLabelTransfer->getValidTo()) {
            return true;
        }

        /** @var \DateTime $validToDate */
        $validToDate = $productLabelTransfer->getValidTo();

        if ($validToDate->getTimestamp() < $now->getTimestamp()) {
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
        return ProductLabelConfig::RESOURCE_TYPE_PRODUCT_LABEL_DICTIONARY;
    }

    /**
     * @return bool
     */
    protected function isStorageTableJoinWithLocaleEnabled()
    {
        return true;
    }

}
