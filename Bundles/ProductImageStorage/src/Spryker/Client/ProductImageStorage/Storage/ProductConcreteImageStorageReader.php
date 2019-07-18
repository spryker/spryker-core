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
use Spryker\Client\ProductImageStorage\ProductImageStorageConfig;
use Spryker\Shared\ProductImageStorage\ProductImageStorageConfig as ProductImageStorageConstants;

class ProductConcreteImageStorageReader implements ProductConcreteImageStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToStorageInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Client\ProductImageStorage\Storage\ProductImageStorageKeyGeneratorInterface
     */
    protected $productImageStorageKeyGenerator;

    /**
     * @param \Spryker\Client\ProductImageStorage\Dependency\Client\ProductImageStorageToStorageInterface $storageClient
     * @param \Spryker\Client\ProductImageStorage\Storage\ProductImageStorageKeyGeneratorInterface $productImageStorageKeyGenerator
     */
    public function __construct(ProductImageStorageToStorageInterface $storageClient, ProductImageStorageKeyGeneratorInterface $productImageStorageKeyGenerator)
    {
        $this->storageClient = $storageClient;
        $this->productImageStorageKeyGenerator = $productImageStorageKeyGenerator;
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
            $clientLocatorClassName = Locator::class;
            /** @var \Spryker\Client\Product\ProductClientInterface $productClient */
            $productClient = $clientLocatorClassName::getInstance()->product()->client();
            $collectorData = $productClient->getProductConcreteByIdAndLocale($idProductConcrete, $locale);

            $imageSets = $collectorData['imageSets'];

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

        return $this->findProductImageProductStorageTransfer($key);
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer|null
     */
    protected function findProductImageProductStorageTransfer($key)
    {
        $imageData = $this->storageClient->get($key);

        if (!$imageData) {
            return null;
        }

        $productImageStorageTransfer = new ProductConcreteImageStorageTransfer();
        $productImageStorageTransfer->fromArray($imageData, true);

        return $productImageStorageTransfer;
    }
}
