<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Exporter;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductExportCriteriaTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\Product\ProductConfig;
use Spryker\Zed\Store\Business\Exception\StoreReferenceNotFoundException;

class ProductEventBusExporter implements ProductExporterInterface
{
    use LoggerTrait;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface
     */
    protected $eventFacade;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface
     */
    protected $productConcreteManager;

    /**
     * @var \Spryker\Zed\Product\ProductConfig
     */
    protected $productConfig;

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface $eventFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToStoreInterface $storeFacade
     * @param \Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface $productConcreteManager
     * @param \Spryker\Zed\Product\ProductConfig $productConfig
     */
    public function __construct(
        ProductToEventInterface $eventFacade,
        ProductToStoreInterface $storeFacade,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductConfig $productConfig
    ) {
        $this->eventFacade = $eventFacade;
        $this->storeFacade = $storeFacade;
        $this->productConcreteManager = $productConcreteManager;
        $this->productConfig = $productConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductExportCriteriaTransfer $productExportCriteriaTransfer
     *
     * @return void
     */
    public function export(ProductExportCriteriaTransfer $productExportCriteriaTransfer): void
    {
        try {
            $idStore = $this->getStoreTransfer($productExportCriteriaTransfer)->getIdStoreOrFail();
        } catch (StoreReferenceNotFoundException $storeReferenceNotFoundException) {
            $this->getLogger()->error($storeReferenceNotFoundException->getMessage());

            return;
        } catch (NullValueException $nullValueException) {
            $this->getLogger()->error($nullValueException->getMessage());

            return;
        }

        $productConcreteIdChunks = $this->productConcreteManager
            ->getAllProductConcreteIdsByChunks($this->productConfig->getProductExportPublishChunkSize(), $idStore);

        foreach ($productConcreteIdChunks as $productConcreteIds) {
            $eventEntityTransfers = $this->createEventTransfers($productConcreteIds);

            $this->eventFacade->triggerBulk(ProductEvents::PRODUCT_CONCRETE_EXPORT, $eventEntityTransfers);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductExportCriteriaTransfer $productExportCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getStoreTransfer(ProductExportCriteriaTransfer $productExportCriteriaTransfer): StoreTransfer
    {
        return $this->storeFacade->getStoreByStoreReference($productExportCriteriaTransfer->getStoreReferenceOrFail());
    }

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\EventEntityTransfer>
     */
    protected function createEventTransfers(array $productConcreteIds): array
    {
        $result = [];

        foreach ($productConcreteIds as $productConcreteId) {
            $result[] = (new EventEntityTransfer())->setId($productConcreteId);
        }

        return $result;
    }
}
