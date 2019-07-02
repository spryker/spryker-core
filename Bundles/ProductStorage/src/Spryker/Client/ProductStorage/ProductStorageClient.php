<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductStorage\ProductStorageFactory getFactory()
 */
class ProductStorageClient extends AbstractClient implements ProductStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use findProductAbstractStorageData($idProductConcrete, $localeName)
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    public function getProductAbstractStorageData($idProductAbstract, $localeName)
    {
        return $this->getFactory()
            ->createProductAbstractStorageReader()
            ->getProductAbstractStorageData($idProductAbstract, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use getProductConcreteStorageData($idProductConcrete, $localeName)
     *
     * @param int $idProductConcrete
     * @param string $localeName
     *
     * @return array
     */
    public function getProductConcreteStorageData($idProductConcrete, $localeName)
    {
        return $this->getFactory()
            ->createProductConcreteStorageReader()
            ->getProductConcreteStorageData($idProductConcrete, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(int $idProductAbstract, string $localeName): ?array
    {
        return $this->getFactory()
            ->createProductAbstractStorageReader()
            ->findProductAbstractStorageData($idProductAbstract, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductAbstractViewTransfer(int $idProductAbstract, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer
    {
        return $this->getFactory()
            ->createProductAbstractViewTransferFinder()
            ->findProductViewTransfer($idProductAbstract, $localeName, $selectedAttributes);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageData(int $idProductConcrete, string $localeName): ?array
    {
        return $this->getFactory()
            ->createProductConcreteStorageReader()
            ->findProductConcreteStorageData($idProductConcrete, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer[]
     */
    public function getProductConcreteStorageTransfers(array $productIds): array
    {
        return $this->getFactory()
            ->createProductConcreteStorageReader()
            ->getProductConcreteStorageTransfersForCurrentLocale($productIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    public function findProductConcreteViewTransfer(int $idProductConcrete, string $localeName, array $selectedAttributes = []): ?ProductViewTransfer
    {
        return $this->getFactory()
            ->createProductConcreteViewTransferFinder()
            ->findProductViewTransfer($idProductConcrete, $localeName, $selectedAttributes);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $data
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductStorageData(array $data, $localeName, array $selectedAttributes = [])
    {
        return $this->getFactory()
            ->createProductStorageDataMapper()
            ->mapProductStorageData($localeName, $data, $selectedAttributes);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $data
     * @param string $localeName
     * @param array $selectedAttributes
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function mapProductAbstractStorageData(array $data, $localeName, array $selectedAttributes = [])
    {
        return $this->getFactory()
            ->createProductAbstractStorageDataMapper()
            ->mapProductStorageData($localeName, $data, $selectedAttributes);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isProductAbstractRestricted(int $idProductAbstract): bool
    {
        return $this->getFactory()
            ->createProductAbstractStorageReader()
            ->isProductAbstractRestricted($idProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteRestricted(int $idProductConcrete): bool
    {
        return $this->getFactory()
            ->createProductConcreteStorageReader()
            ->isProductConcreteRestricted($idProductConcrete);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $mappingType
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array
    {
        return $this->getFactory()
            ->createProductAbstractStorageReader()
            ->findProductAbstractStorageDataByMapping($mappingType, $identifier, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $mappingType
     * @param string $identifier
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataByMapping(string $mappingType, string $identifier, string $localeName): ?array
    {
        return $this->getFactory()
            ->createProductConcreteStorageReader()
            ->findProductConcreteStorageDataByMapping($mappingType, $identifier, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $mappingType
     * @param string $identifier
     *
     * @return array|null
     */
    public function findProductConcreteStorageDataByMappingForCurrentLocale(string $mappingType, string $identifier): ?array
    {
        return $this->getFactory()
            ->createProductConcreteStorageReader()
            ->findProductConcreteStorageDataByMappingForCurrentLocale($mappingType, $identifier);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $productStorageData
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mapProductStorageDataToProductConcreteTransfer(array $productStorageData): ProductConcreteTransfer
    {
        return $this->getFactory()
            ->createProductStorageToProductConcreteTransferDataMapper()
            ->mapProductStorageDataToProductConcreteTransfer($productStorageData);
    }
}
