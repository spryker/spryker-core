<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelCollector\Business\Collector\Storage;

use Generated\Shared\Transfer\ProductLabelStorageProjectionTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface;
use Spryker\Shared\ProductLabel\ProductLabelConfig;
use Spryker\Zed\Collector\Business\Collector\Storage\AbstractStoragePropelCollector;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;

class LabelDictionaryCollector extends AbstractStoragePropelCollector
{

    /**
     * @var \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface $productLabelFacade
     */
    public function __construct(
        UtilDataReaderServiceInterface $utilDataReaderService,
        ProductLabelFacadeInterface $productLabelFacade
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
        if (!$productLabelTransfer->getValidFrom() || !$productLabelTransfer->getValidTo()) {
            return true;
        }

        $now = new \DateTime();

        /** @var \DateTime $validFromDate */
        $validFromDate = $productLabelTransfer->getValidFrom();

        if ($validFromDate->getTimestamp() > $now->getTimestamp()) {
            return false;
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
    protected function getNameAttributeForCurrentLocale(ProductLabelTransfer $productLabelTransfer) {
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
