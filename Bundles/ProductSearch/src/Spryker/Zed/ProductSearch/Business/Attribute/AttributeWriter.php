<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Attribute;

use Exception;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Generated\Shared\Transfer\ProductSearchAttributeTransfer;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttribute;
use Spryker\Shared\ProductSearch\Code\KeyBuilder\GlossaryKeyBuilderInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToProductInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;

class AttributeWriter implements AttributeWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Shared\ProductSearch\Code\KeyBuilder\GlossaryKeyBuilderInterface
     */
    protected $glossaryKeyBuilder;

    /**
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQueryContainer
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToProductInterface $productFacade
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToGlossaryInterface $glossaryFacade
     * @param \Spryker\Shared\ProductSearch\Code\KeyBuilder\GlossaryKeyBuilderInterface $glossaryKeyBuilder
     */
    public function __construct(
        ProductSearchQueryContainerInterface $productSearchQueryContainer,
        ProductSearchToProductInterface $productFacade,
        ProductSearchToLocaleInterface $localeFacade,
        ProductSearchToGlossaryInterface $glossaryFacade,
        GlossaryKeyBuilderInterface $glossaryKeyBuilder
    ) {
        $this->productSearchQueryContainer = $productSearchQueryContainer;
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
        $this->glossaryFacade = $glossaryFacade;
        $this->glossaryKeyBuilder = $glossaryKeyBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function create(ProductSearchAttributeTransfer $productSearchAttributeTransfer)
    {
        $productSearchAttributeTransfer
            ->requireKey()
            ->requireFilterType();

        $this->productSearchQueryContainer
            ->getConnection()
            ->beginTransaction();

        try {
            $productAttributeKeyTransfer = $this->findOrCreateProductAttributeKey($productSearchAttributeTransfer);
            $productSearchAttributeTransfer = $this->createProductSearchAttributeEntity($productSearchAttributeTransfer, $productAttributeKeyTransfer);
            $this->saveGlossaryKeyIfNotExists($productAttributeKeyTransfer);
            $this->saveAttributeKeyTranslations($productSearchAttributeTransfer);

            $this->productSearchQueryContainer
                ->getConnection()
                ->commit();
        } catch (Exception $e) {
            $this->productSearchQueryContainer
                ->getConnection()
                ->rollBack();

            throw $e;
        }

        return $productSearchAttributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    public function update(ProductSearchAttributeTransfer $productSearchAttributeTransfer)
    {
        $productSearchAttributeTransfer
            ->requireKey()
            ->requireIdProductSearchAttribute();

        $this->productSearchQueryContainer
            ->getConnection()
            ->beginTransaction();

        try {
            $productAttributeKeyTransfer = $this->findOrCreateProductAttributeKey($productSearchAttributeTransfer);
            $productSearchAttributeTransfer = $this->updateProductSearchAttributeEntity($productSearchAttributeTransfer, $productAttributeKeyTransfer);
            $this->saveGlossaryKeyIfNotExists($productAttributeKeyTransfer);
            $this->saveAttributeKeyTranslations($productSearchAttributeTransfer);

            $this->productSearchQueryContainer
                ->getConnection()
                ->commit();
        } catch (Exception $e) {
            $this->productSearchQueryContainer
                ->getConnection()
                ->rollBack();

            throw $e;
        }

        return $productSearchAttributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @return void
     */
    public function delete(ProductSearchAttributeTransfer $productSearchAttributeTransfer)
    {
        $idProductSearchAttribute = $productSearchAttributeTransfer
            ->requireIdProductSearchAttribute()
            ->getIdProductSearchAttribute();

        $this->productSearchQueryContainer
            ->queryProductSearchAttribute()
            ->filterByIdProductSearchAttribute($idProductSearchAttribute)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer[] $productSearchAttributes
     *
     * @throws \Exception
     *
     * @return void
     */
    public function reorder(array $productSearchAttributes)
    {
        $this->productSearchQueryContainer
            ->getConnection()
            ->beginTransaction();

        try {
            foreach ($productSearchAttributes as $productSearchAttributeTransfer) {
                $productSearchAttributeTransfer
                    ->requireIdProductSearchAttribute()
                    ->requirePosition();

                $productSearchAttributeEntity = $this->productSearchQueryContainer
                    ->queryProductSearchAttribute()
                    ->findOneByIdProductSearchAttribute($productSearchAttributeTransfer->getIdProductSearchAttribute());

                $productSearchAttributeEntity
                    ->setPosition($productSearchAttributeTransfer->getPosition())
                    ->save();
            }

            $this->productSearchQueryContainer
                ->getConnection()
                ->commit();
        } catch (Exception $e) {
            $this->productSearchQueryContainer
                ->getConnection()
                ->rollBack();

            throw $e;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAttributeKeyTransfer
     */
    protected function findOrCreateProductAttributeKey(ProductSearchAttributeTransfer $productSearchAttributeTransfer)
    {
        if ($this->productFacade->hasProductAttributeKey($productSearchAttributeTransfer->getKey())) {
            $productAttributeKeyTransfer = $this->productFacade->findProductAttributeKey($productSearchAttributeTransfer->getKey());

            return $productAttributeKeyTransfer;
        }

        $productAttributeKeyTransfer = new ProductAttributeKeyTransfer();
        $productAttributeKeyTransfer->setKey($productSearchAttributeTransfer->getKey());
        $productAttributeKeyTransfer = $this->productFacade->createProductAttributeKey($productAttributeKeyTransfer);

        return $productAttributeKeyTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    protected function createProductSearchAttributeEntity(
        ProductSearchAttributeTransfer $productSearchAttributeTransfer,
        ProductAttributeKeyTransfer $productAttributeKeyTransfer
    ) {
        $productSearchAttributeEntity = new SpyProductSearchAttribute();
        $productSearchAttributeEntity->fromArray($productSearchAttributeTransfer->modifiedToArray());
        $productSearchAttributeEntity->setFkProductAttributeKey($productAttributeKeyTransfer->getIdProductAttributeKey());

        $productSearchAttributeEntity->save();
        $productSearchAttributeTransfer->fromArray($productSearchAttributeEntity->toArray(), true);

        return $productSearchAttributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSearchAttributeTransfer
     */
    protected function updateProductSearchAttributeEntity(
        ProductSearchAttributeTransfer $productSearchAttributeTransfer,
        ProductAttributeKeyTransfer $productAttributeKeyTransfer
    ) {
        $productSearchAttributeEntity = $this->productSearchQueryContainer
            ->queryProductSearchAttribute()
            ->findOneByIdProductSearchAttribute($productSearchAttributeTransfer->getIdProductSearchAttribute());

        $productSearchAttributeEntity->fromArray($productSearchAttributeTransfer->modifiedToArray());

        $productSearchAttributeEntity->setFkProductAttributeKey($productAttributeKeyTransfer->getIdProductAttributeKey());

        $productSearchAttributeEntity->save();

        return $productSearchAttributeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAttributeKeyTransfer $productAttributeKeyTransfer
     *
     * @return void
     */
    protected function saveGlossaryKeyIfNotExists(ProductAttributeKeyTransfer $productAttributeKeyTransfer)
    {
        $glossaryKey = $this->glossaryKeyBuilder->buildGlossaryKey($productAttributeKeyTransfer->getKey());
        if ($this->glossaryFacade->hasKey($glossaryKey) === false) {
            $this->glossaryFacade->createKey($glossaryKey);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSearchAttributeTransfer $productSearchAttributeTransfer
     *
     * @return void
     */
    protected function saveAttributeKeyTranslations(ProductSearchAttributeTransfer $productSearchAttributeTransfer)
    {
        foreach ($productSearchAttributeTransfer->getLocalizedKeys() as $localizedAttributeKeyTransfer) {
            $localizedAttributeKeyTransfer->requireLocaleName();
            $localeTransfer = $this->getLocaleByName($localizedAttributeKeyTransfer->getLocaleName());

            $this->saveAttributeKeyToGlossary($productSearchAttributeTransfer->getKey(), $localizedAttributeKeyTransfer->getKeyTranslation(), $localeTransfer);
        }
    }

    /**
     * @param string $key
     * @param string $keyTranslation
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function saveAttributeKeyToGlossary($key, $keyTranslation, LocaleTransfer $localeTransfer)
    {
        $attributeGlossaryKey = $this->glossaryKeyBuilder->buildGlossaryKey($key);

        if ($this->glossaryFacade->hasTranslation($attributeGlossaryKey, $localeTransfer)) {
            $this->glossaryFacade->updateAndTouchTranslation(
                $attributeGlossaryKey,
                $localeTransfer,
                $keyTranslation,
                true
            );

            return;
        }

        $this->glossaryFacade->createAndTouchTranslation(
            $attributeGlossaryKey,
            $localeTransfer,
            $keyTranslation,
            true
        );
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleByName($localeName)
    {
        return $this->localeFacade->getLocale($localeName);
    }
}
