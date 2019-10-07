<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfile\Helper;

use ArrayObject;
use Codeception\Module;
use Generated\Shared\DataBuilder\MerchantProfileBuilder;
use Generated\Shared\DataBuilder\MerchantProfileGlossaryAttributeValuesBuilder;
use Generated\Shared\DataBuilder\MerchantProfileLocalizedGlossaryAttributesBuilder;
use Generated\Shared\DataBuilder\UrlBuilder;
use Generated\Shared\Transfer\MerchantProfileLocalizedGlossaryAttributesTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class MerchantProfileHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer|null $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function haveMerchantProfile(?MerchantTransfer $merchantTransfer = null): MerchantProfileTransfer
    {
        $merchantProfileTransfer = (new MerchantProfileBuilder())->build();
        $merchantProfileTransfer->setIdMerchantProfile(null);
        if ($merchantTransfer !== null) {
            $merchantProfileTransfer->setFkMerchant($merchantTransfer->getIdMerchant());
        }

        $merchantProfileTransfer = $this->addMerchantProfileLocalizedGlossaryAttributes($merchantProfileTransfer);
        $merchantProfileTransfer->setUrlCollection($this->createMerchantUrlCollection());

        return $this->getLocator()
            ->merchantProfile()
            ->facade()
            ->saveMerchantProfile($merchantProfileTransfer);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasGlossaryKey(string $key): bool
    {
        $glossaryFacade = $this->getLocator()->glossary()->facade();

        return $glossaryFacade->hasKey($key);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function addMerchantProfileLocalizedGlossaryAttributes(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $localeTransferCollection = $this->getLocaleTransferCollection();
        $merchantProfileLocalizedGlossaryAttributes = new ArrayObject();
        foreach ($localeTransferCollection as $localeTransfer) {
            $merchantProfileLocalizedGlossaryAttributesTransfer = (new MerchantProfileLocalizedGlossaryAttributesBuilder())->build();
            $merchantProfileLocalizedGlossaryAttributesTransfer = $this->addMerchantProfileGlossaryAttributeValues($merchantProfileLocalizedGlossaryAttributesTransfer);
            $merchantProfileLocalizedGlossaryAttributesTransfer->setLocaleName($localeTransfer->getLocaleName());
            $merchantProfileLocalizedGlossaryAttributesTransfer->setFkLocale($localeTransfer->getIdLocale());
            $merchantProfileLocalizedGlossaryAttributes->append($merchantProfileLocalizedGlossaryAttributesTransfer);
        }
        $merchantProfileTransfer->setMerchantProfileLocalizedGlossaryAttributes($merchantProfileLocalizedGlossaryAttributes);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileLocalizedGlossaryAttributesTransfer $merchantProfileLocalizedGlossaryAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileLocalizedGlossaryAttributesTransfer
     */
    protected function addMerchantProfileGlossaryAttributeValues(
        MerchantProfileLocalizedGlossaryAttributesTransfer $merchantProfileLocalizedGlossaryAttributesTransfer
    ): MerchantProfileLocalizedGlossaryAttributesTransfer {
        $merchantProfileGlossaryAttributeValuesTransfer = (new MerchantProfileGlossaryAttributeValuesBuilder())->build();
        $merchantProfileLocalizedGlossaryAttributesTransfer->setMerchantProfileGlossaryAttributeValues($merchantProfileGlossaryAttributeValuesTransfer);

        return $merchantProfileLocalizedGlossaryAttributesTransfer;
    }

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\UrlTransfer[]
     */
    public function createMerchantUrlCollection(): ArrayObject
    {
        $urlTransfer = (new UrlBuilder())->build();
        $urlCollection = new ArrayObject();
        $urlCollection->append($urlTransfer);

        return $urlCollection;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function getLocaleTransferCollection(): array
    {
        return $this->getLocator()->locale()->facade()->getLocaleCollection();
    }
}
