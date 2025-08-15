<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageStorage\Storage;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;
use Spryker\Client\Kernel\Locator;
use Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToStorageInterface;
use Spryker\Client\ProductImageStorage\Expander\ProductImageStorageExpanderInterface;
use Spryker\Client\ProductImageStorage\ProductImageStorageConfig;
use Spryker\Shared\ProductImageStorage\ProductImageStorageConfig as ProductImageStorageConstants;

class ProductConcreteImageStorageReader implements ProductConcreteImageStorageReaderInterface
{
    /**
     * @param \Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToStorageInterface $storageClient
     * @param \Spryker\Client\ProductImageStorage\Storage\ProductImageStorageKeyGeneratorInterface $productImageStorageKeyGenerator
     * @param \Spryker\Client\ProductImageStorage\Expander\ProductImageStorageExpanderInterface|null $productImageStorageExpander
     */
    public function __construct(
        protected ProductImageStorageToStorageInterface $storageClient,
        protected ProductImageStorageKeyGeneratorInterface $productImageStorageKeyGenerator,
        protected ?ProductImageStorageExpanderInterface $productImageStorageExpander = null
    ) {
    }

    /**
     * @param int $idProductConcrete
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer|null
     */
    public function findProductImageConcreteStorageTransfer($idProductConcrete, $locale)
    {
        if (ProductImageStorageConfig::isCollectorCompatibilityMode()) {
            $clientLocatorClass = Locator::class;
            /** @var \Generated\Zed\Ide\AutoCompletion&\Spryker\Shared\Kernel\LocatorLocatorInterface $locator */
            $locator = $clientLocatorClass::getInstance();
            $productClient = $locator->product()->client();
            $collectorData = $productClient->getProductConcreteByIdAndLocale($idProductConcrete, $locale);

            $imageSets = $collectorData['imageSets'];

            /** @var \ArrayObject<int, array<string, mixed>> $formattedImageSets */
            $formattedImageSets = new ArrayObject();
            foreach ($imageSets as $imageSetName => $images) {
                $formattedImageSets[] = [
                    'name' => $imageSetName,
                    'images' => $images,
                ];
            }
            $imageData = [
                'id_product_construct' => $idProductConcrete,
                'image_sets' => $formattedImageSets,
            ];

            $productImageStorageTransfer = new ProductConcreteImageStorageTransfer();
            $productImageStorageTransfer->fromArray($imageData, true);

            return $productImageStorageTransfer;
        }

        $key = $this->productImageStorageKeyGenerator->generateKey(ProductImageStorageConstants::PRODUCT_CONCRETE_IMAGE_RESOURCE_NAME, $idProductConcrete, $locale);
        $productConcreteImageStorageTransfer = $this->findProductImageProductStorageTransfer($key);

        if (!$productConcreteImageStorageTransfer || !$this->productImageStorageExpander) {
            return $productConcreteImageStorageTransfer;
        }

        return $this->productImageStorageExpander
            ->expandProductConcreteImageStorageTransferWithProductImageAlternativeTexts($productConcreteImageStorageTransfer, $locale);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer|null
     */
    protected function findProductImageProductStorageTransfer($key)
    {
        $imageData = $this->storageClient->get($key);

        if (!$imageData && !is_array($imageData)) {
            return null;
        }

        $productImageStorageTransfer = new ProductConcreteImageStorageTransfer();
        $productImageStorageTransfer->fromArray($imageData, true);

        return $productImageStorageTransfer;
    }
}
