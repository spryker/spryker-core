<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProfileGlossaryAttributeValuesTransfer;
use Generated\Shared\Transfer\MerchantProfileLocalizedGlossaryAttributesTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToGlossaryFacadeInterface;
use Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface;
use Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface;

class MerchantProfileMerchantExpander implements MerchantProfileMerchantExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface
     */
    protected MerchantProfileRepositoryInterface $merchantProfileRepository;

    /**
     * @var \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToGlossaryFacadeInterface
     */
    protected MerchantProfileToGlossaryFacadeInterface $glossaryFacade;

    /**
     * @var \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface
     */
    protected MerchantProfileToLocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileRepositoryInterface $merchantProfileRepository
     * @param \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToGlossaryFacadeInterface $glossaryFacade
     * @param \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        MerchantProfileRepositoryInterface $merchantProfileRepository,
        MerchantProfileToGlossaryFacadeInterface $glossaryFacade,
        MerchantProfileToLocaleFacadeInterface $localeFacade
    ) {
        $this->merchantProfileRepository = $merchantProfileRepository;
        $this->glossaryFacade = $glossaryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function expand(MerchantCollectionTransfer $merchantCollectionTransfer): MerchantCollectionTransfer
    {
        if ($merchantCollectionTransfer->getMerchants()->count() === 0) {
            return $merchantCollectionTransfer;
        }

        $merchantProfileCriteriaTransfer = $this->createMerchantProfileCriteriaTransfer($merchantCollectionTransfer);
        $merchantProfileCollectionTransfer = $this->merchantProfileRepository->get($merchantProfileCriteriaTransfer);

        if ($merchantProfileCollectionTransfer->getMerchantProfiles()->count() === 0) {
            return $merchantCollectionTransfer;
        }

        $merchantProfileCollectionTransfer = $this->expandWithLocalizedGlossaryAttributes($merchantProfileCollectionTransfer);

        $merchantProfileTransfersIndexedByIdMerchant = $this->getMerchantProfileTransfersIndexedByIdMerchant($merchantProfileCollectionTransfer);

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $idMerchant = $merchantTransfer->getIdMerchant();

            if (!isset($merchantProfileTransfersIndexedByIdMerchant[$idMerchant])) {
                continue;
            }

            $merchantTransfer->setMerchantProfile($merchantProfileTransfersIndexedByIdMerchant[$idMerchant]);
        }

        return $merchantCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCriteriaTransfer
     */
    protected function createMerchantProfileCriteriaTransfer(
        MerchantCollectionTransfer $merchantCollectionTransfer
    ): MerchantProfileCriteriaTransfer {
        $merchantIds = [];

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantIds[] = $merchantTransfer->getIdMerchantOrFail();
        }

        return (new MerchantProfileCriteriaTransfer())
            ->setMerchantIds($merchantIds);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCollectionTransfer $merchantProfileCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\MerchantProfileTransfer>
     */
    protected function getMerchantProfileTransfersIndexedByIdMerchant(
        MerchantProfileCollectionTransfer $merchantProfileCollectionTransfer
    ): array {
        $merchantProfileTransfersIndexedByIdMerchant = [];

        foreach ($merchantProfileCollectionTransfer->getMerchantProfiles() as $merchantProfileTransfer) {
            $fkMerchant = $merchantProfileTransfer->getFkMerchantOrFail();
            $merchantProfileTransfersIndexedByIdMerchant[$fkMerchant] = $merchantProfileTransfer;
        }

        return $merchantProfileTransfersIndexedByIdMerchant;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCollectionTransfer $merchantProfileCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCollectionTransfer
     */
    protected function expandWithLocalizedGlossaryAttributes(
        MerchantProfileCollectionTransfer $merchantProfileCollectionTransfer
    ): MerchantProfileCollectionTransfer {
        $merchantProfileGlossaryAttributeValues = new ArrayObject();
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        foreach ($merchantProfileCollectionTransfer->getMerchantProfiles() as $merchantProfileTransfer) {
            foreach ($localeTransfers as $localeTransfer) {
                $merchantProfileGlossaryAttributeValues->append(
                    $this->getMerchantProfileLocalizedGlossaryAttributesTransfer($merchantProfileTransfer, $localeTransfer),
                );
            }

            $merchantProfileTransfer->setMerchantProfileLocalizedGlossaryAttributes($merchantProfileGlossaryAttributeValues);
        }

        return $merchantProfileCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileLocalizedGlossaryAttributesTransfer
     */
    protected function getMerchantProfileLocalizedGlossaryAttributesTransfer(
        MerchantProfileTransfer $merchantProfileTransfer,
        LocaleTransfer $localeTransfer
    ): MerchantProfileLocalizedGlossaryAttributesTransfer {
        $merchantProfileLocalizedGlossaryAttributesTransfer = new MerchantProfileLocalizedGlossaryAttributesTransfer();
        $merchantProfileLocalizedGlossaryAttributesTransfer->setLocale($localeTransfer);
        $merchantProfileLocalizedGlossaryAttributesTransfer->setMerchantProfileGlossaryAttributeValues(
            $this->getGlossaryAttributeTranslations($merchantProfileTransfer, $localeTransfer),
        );

        return $merchantProfileLocalizedGlossaryAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileGlossaryAttributeValuesTransfer
     */
    protected function getGlossaryAttributeTranslations(
        MerchantProfileTransfer $merchantProfileTransfer,
        LocaleTransfer $localeTransfer
    ): MerchantProfileGlossaryAttributeValuesTransfer {
        $merchantProfileGlossaryAttributeValuesTransfer = new MerchantProfileGlossaryAttributeValuesTransfer();

        $merchantProfileGlossaryAttributeValuesData = $merchantProfileGlossaryAttributeValuesTransfer->toArray(true, true);
        $merchantProfileData = $merchantProfileTransfer->toArray(true, true);

        foreach ($merchantProfileGlossaryAttributeValuesData as $merchantProfileGlossaryAttributeFieldName => $glossaryAttributeValue) {
            $merchantProfileGlossaryKey = $merchantProfileData[$merchantProfileGlossaryAttributeFieldName];
            if ($merchantProfileGlossaryKey === null) {
                continue;
            }

            $merchantProfileGlossaryAttributeValuesData[$merchantProfileGlossaryAttributeFieldName] = $this->findLocalizedTranslationValue($merchantProfileGlossaryKey, $localeTransfer);
        }

        $merchantProfileGlossaryAttributeValuesTransfer->fromArray($merchantProfileGlossaryAttributeValuesData);

        return $merchantProfileGlossaryAttributeValuesTransfer;
    }

    /**
     * @param string $key
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function findLocalizedTranslationValue(string $key, LocaleTransfer $localeTransfer): ?string
    {
        if ($this->glossaryFacade->hasTranslation($key, $localeTransfer) === false) {
            return null;
        }

        $translationTransfer = $this->glossaryFacade->getTranslation($key, $localeTransfer);

        return $translationTransfer->getIsActive() ? $translationTransfer->getValue() : null;
    }
}
