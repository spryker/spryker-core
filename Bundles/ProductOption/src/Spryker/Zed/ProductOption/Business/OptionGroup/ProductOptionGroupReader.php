<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Business\OptionGroup;

use ArrayObject;
use Generated\Shared\Transfer\ProductOptionGroupTransfer;
use Generated\Shared\Transfer\ProductOptionTranslationTransfer;
use Generated\Shared\Transfer\ProductOptionValueTransfer;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionValue;
use Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleFacadeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionGroupReader implements ProductOptionGroupReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceHydratorInterface
     */
    protected $productOptionValuePriceHydrator;

    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var array<\Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionGroupExpanderPluginInterface>
     */
    protected $productOptionGroupExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceHydratorInterface $productOptionValuePriceHydrator
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleFacadeInterface $localeFacade
     * @param array<\Spryker\Zed\ProductOptionExtension\Dependency\Plugin\ProductOptionGroupExpanderPluginInterface> $productOptionGroupExpanderPlugins
     */
    public function __construct(
        ProductOptionValuePriceHydratorInterface $productOptionValuePriceHydrator,
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        ProductOptionToGlossaryFacadeInterface $glossaryFacade,
        ProductOptionToLocaleFacadeInterface $localeFacade,
        array $productOptionGroupExpanderPlugins
    ) {
        $this->productOptionValuePriceHydrator = $productOptionValuePriceHydrator;
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
        $this->productOptionGroupExpanderPlugins = $productOptionGroupExpanderPlugins;
    }

    /**
     * @param int $idProductOptionGroup
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    public function getProductOptionGroupById($idProductOptionGroup)
    {
        $productOptionGroupEntity = $this->getProductOptionGroupEntityWithValuesAndValuePricesById((int)$idProductOptionGroup);
        $productOptionGroupTransfer = $this->hydrateProductOptionGroupTransfer($productOptionGroupEntity);

        return $this->executeProductOptionGroupExpanderPlugins($productOptionGroupTransfer);
    }

    /**
     * @param int $idProductOptionValue
     *
     * @return bool
     */
    public function checkProductOptionGroupExistenceByProductOptionValueId(int $idProductOptionValue): bool
    {
        return $this->productOptionQueryContainer
            ->queryProductOptionGroupByProductOptionValueId($idProductOptionValue)
            ->exists();
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    protected function hydrateProductOptionGroupTransfer(SpyProductOptionGroup $productOptionGroupEntity)
    {
        $productOptionGroupTransfer = new ProductOptionGroupTransfer();
        $productOptionGroupTransfer->fromArray($productOptionGroupEntity->toArray(), true);

        $availableLocales = $this->localeFacade->getLocaleCollection();

        $productOptionValueTranslations = $this->hydrateProductOptionValues(
            $productOptionGroupEntity,
            $productOptionGroupTransfer,
            $availableLocales,
        );

        $productOptionGroupTransfer->setProductOptionValueTranslations(new ArrayObject($productOptionValueTranslations));

        $groupNameTranslations = $this->getOptionTranslations(
            $availableLocales,
            $productOptionGroupTransfer->getName(),
            $this->createRelatedKeyHash(ProductOptionGroupTransfer::class, $productOptionGroupTransfer->getIdProductOptionGroup()),
        );

        $productOptionGroupTransfer->setGroupNameTranslations(new ArrayObject($groupNameTranslations));

        return $productOptionGroupTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $availableLocales
     * @param string $translationKey
     * @param string $relatedOptionHash
     *
     * @return array<\Generated\Shared\Transfer\ProductOptionTranslationTransfer>
     */
    protected function getOptionTranslations(array $availableLocales, $translationKey, $relatedOptionHash)
    {
        $translations = [];
        foreach ($availableLocales as $localeTransfer) {
            if (!$this->glossaryFacade->hasTranslation($translationKey, $localeTransfer)) {
                continue;
            }

            $translationTransfer = $this->glossaryFacade->getTranslation($translationKey, $localeTransfer);

            $productOptionTranslationTransfer = new ProductOptionTranslationTransfer();
            $productOptionTranslationTransfer->setName($translationTransfer->getValue());
            $productOptionTranslationTransfer->setKey($translationKey);
            $productOptionTranslationTransfer->setLocaleCode($localeTransfer->getLocaleName());
            $productOptionTranslationTransfer->setRelatedOptionHash($relatedOptionHash);

            $translations[] = $productOptionTranslationTransfer;
        }

        return $translations;
    }

    /**
     * @param string $identifierGroup
     * @param string|int $identifier
     *
     * @return string
     */
    protected function createRelatedKeyHash($identifierGroup, $identifier)
    {
        return hash('sha256', $identifierGroup . $identifier);
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     * @param array<\Generated\Shared\Transfer\LocaleTransfer> $availableLocales
     *
     * @return array<\Generated\Shared\Transfer\ProductOptionTranslationTransfer>
     */
    protected function hydrateProductOptionValues(
        SpyProductOptionGroup $productOptionGroupEntity,
        ProductOptionGroupTransfer $productOptionGroupTransfer,
        array $availableLocales
    ) {
        $productOptionValueTranslations = [];
        foreach ($productOptionGroupEntity->getSpyProductOptionValues() as $productOptionValueEntity) {
            $productOptionValueTransfer = $this->hydrateProductOptionValueTransfer($productOptionValueEntity);
            $productOptionValueTransfer->setPrices($this->getPriceCollection($productOptionValueEntity));

            $relatedOptionHash = $this->createRelatedKeyHash(
                SpyProductOptionValue::class,
                $productOptionValueEntity->getIdProductOptionValue(),
            );
            $productOptionValueTransfer->setOptionHash($relatedOptionHash);

            $productOptionGroupTransfer->addProductOptionValue($productOptionValueTransfer);

            $valueTranslations = $this->getOptionTranslations(
                $availableLocales,
                $productOptionValueTransfer->getValue(),
                $relatedOptionHash,
            );

            $productOptionValueTranslations = array_merge($productOptionValueTranslations, $valueTranslations);
        }

        return $productOptionValueTranslations;
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $productOptionValueEntity
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\MoneyValueTransfer>
     */
    protected function getPriceCollection(SpyProductOptionValue $productOptionValueEntity)
    {
        return $this->productOptionValuePriceHydrator->getMoneyValueCollection(
            $productOptionValueEntity->getProductOptionValuePrices(),
        );
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionValue $productOptionValueEntity
     *
     * @return \Generated\Shared\Transfer\ProductOptionValueTransfer
     */
    protected function hydrateProductOptionValueTransfer(SpyProductOptionValue $productOptionValueEntity)
    {
        $productOptionValueTransfer = new ProductOptionValueTransfer();
        $productOptionValueTransfer->fromArray($productOptionValueEntity->toArray(), true);

        return $productOptionValueTransfer;
    }

    /**
     * @param int $idProductOptionGroup
     *
     * @throws \Spryker\Zed\ProductOption\Business\Exception\ProductOptionGroupNotFoundException
     *
     * @return \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup
     */
    protected function getProductOptionGroupEntityWithValuesAndValuePricesById($idProductOptionGroup): SpyProductOptionGroup
    {
        $productOptionGroupCollection = $this->productOptionQueryContainer
            ->queryProductOptionGroupWithProductOptionValuesAndProductOptionValuePricesById($idProductOptionGroup)
            ->find();

        if ($productOptionGroupCollection->count() === 0) {
            throw new ProductOptionGroupNotFoundException(
                sprintf(
                    'Product Option Group with id "%d" not found.',
                    $idProductOptionGroup,
                ),
            );
        }

        return $productOptionGroupCollection->getFirst();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionGroupTransfer $productOptionGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupTransfer
     */
    protected function executeProductOptionGroupExpanderPlugins(
        ProductOptionGroupTransfer $productOptionGroupTransfer
    ): ProductOptionGroupTransfer {
        foreach ($this->productOptionGroupExpanderPlugins as $productOptionGroupExpanderPlugin) {
            $productOptionGroupTransfer = $productOptionGroupExpanderPlugin->expand($productOptionGroupTransfer);
        }

        return $productOptionGroupTransfer;
    }
}
