<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Persistence;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelStore;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\ProductLabel\Persistence\Exception\MissingProductLabelException;

/**
 * @method \Spryker\Zed\ProductLabel\Persistence\ProductLabelPersistenceFactory getFactory()
 */
class ProductLabelEntityManager extends AbstractEntityManager implements ProductLabelEntityManagerInterface
{
    /**
     * @var string
     */
    protected const KEY_NAME = 'name';

    /**
     * @var string
     */
    protected const KEY_LOCALES = 'locales';

    /**
     * @var string
     */
    protected const COL_NAME_ALIAS = 'default_name';

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function createProductLabel(ProductLabelTransfer $productLabelTransfer): ProductLabelTransfer
    {
        $productLabelMapper = $this->getFactory()->createProductLabelMapper();

        $productLabelEntity = $productLabelMapper->mapProductLabelTransferToProductLabelEntity(
            $productLabelTransfer,
            new SpyProductLabel(),
        );
        $productLabelEntity->save();

        return $productLabelMapper->mapProductLabelEntityToProductLabelTransfer(
            $productLabelEntity,
            $productLabelTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @throws \Spryker\Zed\ProductLabel\Persistence\Exception\MissingProductLabelException
     *
     * @return array<string>
     */
    public function updateProductLabel(ProductLabelTransfer $productLabelTransfer): array
    {
        $productLabelEntity = $this->getFactory()
            ->createProductLabelQuery()
            ->findOneByIdProductLabel($productLabelTransfer->getIdProductLabel());

        if ($productLabelEntity === null) {
            throw new MissingProductLabelException(sprintf(
                'Could not find product label for id "%s"',
                $productLabelTransfer->getIdProductLabel(),
            ));
        }

        $productLabelMapper = $this->getFactory()->createProductLabelMapper();

        $productLabelEntity = $productLabelMapper->mapProductLabelTransferToProductLabelEntity(
            $productLabelTransfer,
            $productLabelEntity,
        );

        $modifiedColumns = $productLabelEntity->getModifiedColumns();

        $productLabelEntity->save();

        return $modifiedColumns;
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabel(int $idProductLabel): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $productLabelCollection */
        $productLabelCollection = $this->getFactory()
            ->createProductLabelQuery()
            ->findByIdProductLabel($idProductLabel);

        $productLabelCollection->delete();
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabelStoreRelations(int $idProductLabel): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $productLabelStoreCollection */
        $productLabelStoreCollection = $this->getFactory()
            ->createProductLabelStoreQuery()
            ->findByFkProductLabel($idProductLabel);

        $productLabelStoreCollection->delete();
    }

    /**
     * @param int $idProductLabel
     *
     * @return void
     */
    public function deleteProductLabelLocalizedAttributes(int $idProductLabel): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $productLocalizedAttributesCollection */
        $productLocalizedAttributesCollection = $this->getFactory()
            ->createLocalizedAttributesQuery()
            ->findByFkProductLabel($idProductLabel);

        $productLocalizedAttributesCollection->delete();
    }

    /**
     * @param int $idProductLabel
     * @param array<int> $productAbstractIds
     *
     * @return void
     */
    public function deleteProductLabelProductAbstractRelations(int $idProductLabel, array $productAbstractIds = []): void
    {
        $productRelationQuery = $this->getFactory()
            ->createProductRelationQuery()
            ->filterByFkProductLabel($idProductLabel);

        if ($productAbstractIds) {
            $productRelationQuery->filterByFkProductAbstract_In($productAbstractIds);
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $productRelationCollection */
        $productRelationCollection = $productRelationQuery->find();
        $productRelationCollection->delete();
    }

    /**
     * @param array<int> $idStores
     * @param int $idProductLabel
     *
     * @return void
     */
    public function removeProductLabelStoreRelationForStores(array $idStores, int $idProductLabel): void
    {
        if ($idStores === []) {
            return;
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $productLabelStoreCollection */
        $productLabelStoreCollection = $this->getFactory()
            ->createProductLabelStoreQuery()
            ->filterByFkProductLabel($idProductLabel)
            ->filterByFkStore_In($idStores)
            ->find();
        $productLabelStoreCollection->delete();
    }

    /**
     * @param array<int> $idStores
     * @param int $idProductLabel
     *
     * @return void
     */
    public function createProductLabelStoreRelationForStores(array $idStores, int $idProductLabel): void
    {
        foreach ($idStores as $idStore) {
            $productLabelStoreEntity = new SpyProductLabelStore();
            $productLabelStoreEntity->setFkStore($idStore)
                ->setFkProductLabel($idProductLabel)
                ->save();
        }
    }

    /**
     * @return void
     */
    public function createMissingLocalizedAttributes(): void
    {
        $existingLocalizedAttributes = $this->getFactory()->createLocalizedAttributesQuery()
            ->rightJoinWithSpyProductLabel()
            ->withColumn(SpyProductLabelTableMap::COL_NAME, static::COL_NAME_ALIAS)
            ->find();

        $existingLocalizedAttributesIndexedByFkProductLabel = $this->getExistingLocalizedAttributesIndexedByFkProductLabel($existingLocalizedAttributes);
        $missingLocalizedAttributesIndexedByFkProductLabel = $this->getMissingLocalizedAttributesIndexedByFkProductLabel($existingLocalizedAttributesIndexedByFkProductLabel);

        foreach ($missingLocalizedAttributesIndexedByFkProductLabel as $fkProductLabel => $missingLocales) {
            $this->createMissingLocalizedAttributesForProductLabel(
                $fkProductLabel,
                $missingLocales,
                $existingLocalizedAttributesIndexedByFkProductLabel[$fkProductLabel][static::KEY_NAME],
            );
        }
    }

    /**
     * @param int $fkProductLabel
     * @param array<int> $missingLocales
     * @param string $name
     *
     * @return void
     */
    protected function createMissingLocalizedAttributesForProductLabel(int $fkProductLabel, array $missingLocales, string $name): void
    {
        foreach ($missingLocales as $fkLocale) {
            $productLabelLocalizedAttributesEntity = (new SpyProductLabelLocalizedAttributes())
                ->setFkProductLabel($fkProductLabel)
                ->setFkLocale($fkLocale)
                ->setName($name)
                ->save();
        }
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes> $existingLocalizedAttributeslizedAttributes
     *
     * @return array<int, array<mixed>>
     */
    protected function getExistingLocalizedAttributesIndexedByFkProductLabel(Collection $existingLocalizedAttributeslizedAttributes): array
    {
        $existingLocalizedAttributesIndexedByFkProductLabel = [];

        foreach ($existingLocalizedAttributeslizedAttributes as $localizedAttributesEntity) {
            $existingLocalizedAttributesIndexedByFkProductLabel[$localizedAttributesEntity->getFkProductLabel()][static::KEY_NAME] = $localizedAttributesEntity->getVirtualColumn(static::COL_NAME_ALIAS);
            $existingLocalizedAttributesIndexedByFkProductLabel[$localizedAttributesEntity->getFkProductLabel()][static::KEY_LOCALES][$localizedAttributesEntity->getFkLocale()] = true;
        }

        return $existingLocalizedAttributesIndexedByFkProductLabel;
    }

    /**
     * @param array<int, array<mixed>> $existingLocalizedAttributesIndexedByFkProductLabel
     *
     * @return array<int, array<int>>
     */
    protected function getMissingLocalizedAttributesIndexedByFkProductLabel(array $existingLocalizedAttributesIndexedByFkProductLabel): array
    {
        $localeIds = array_keys($this->getFactory()->getLocaleFacade()->getAvailableLocales());

        $missingLocalizedAttributesIndexedByFkProductLabel = [];
        foreach ($existingLocalizedAttributesIndexedByFkProductLabel as $fkProductLabel => $locales) {
            $missingLocales = array_diff($localeIds, array_keys($locales[static::KEY_LOCALES]));

            if ($missingLocales === []) {
                continue;
            }

            $missingLocalizedAttributesIndexedByFkProductLabel[$fkProductLabel] = $missingLocales;
        }

        return $missingLocalizedAttributesIndexedByFkProductLabel;
    }
}
