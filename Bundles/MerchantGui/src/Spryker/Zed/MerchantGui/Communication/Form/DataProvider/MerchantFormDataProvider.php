<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\MerchantGui\Communication\Form\MerchantCreateForm;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToLocaleFacadeInterface;
use Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface;
use Spryker\Zed\MerchantGui\MerchantGuiConfig;

class MerchantFormDataProvider
{
    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantGui\MerchantGuiConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantGui\MerchantGuiConfig $config
     * @param \Spryker\Zed\MerchantGui\Dependency\Facade\MerchantGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        MerchantGuiToMerchantFacadeInterface $merchantFacade,
        MerchantGuiConfig $config,
        MerchantGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->config = $config;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int|null $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getData(?int $idMerchant = null): MerchantTransfer
    {
        $merchantTransfer = new MerchantTransfer();

        if ($idMerchant) {
            $merchantCriteriaTransfer = new MerchantCriteriaTransfer();
            $merchantCriteriaTransfer->setIdMerchant($idMerchant);
            $merchantTransfer = $this->merchantFacade->findOne($merchantCriteriaTransfer);
        }

        if (!$merchantTransfer->getStoreRelation()) {
            $merchantTransfer->setStoreRelation(new StoreRelationTransfer());
        }

        $merchantTransfer = $this->setInitialUrlCollection($merchantTransfer);

        return $merchantTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    protected function setInitialUrlCollection(MerchantTransfer $merchantTransfer): MerchantTransfer
    {
        $merchantUrlCollection = $merchantTransfer->getUrlCollection();

        $urlCollection = new ArrayObject();
        $availableLocaleTransfers = $this->localeFacade->getLocaleCollection();

        foreach ($availableLocaleTransfers as $localeTransfer) {
            $urlCollection->append(
                $this->setUrlPrefixToUrlTransfer($merchantUrlCollection, $localeTransfer)
            );
        }
        $merchantTransfer->setUrlCollection($urlCollection);

        return $merchantTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\UrlTransfer[] $merchantUrlCollection
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function setUrlPrefixToUrlTransfer($merchantUrlCollection, LocaleTransfer $localeTransfer): UrlTransfer
    {
        $urlTransfer = new UrlTransfer();
        foreach ($merchantUrlCollection as $urlTransfer) {
            if ($urlTransfer->getFkLocale() === $localeTransfer->getIdLocale()) {
                $urlTransfer->fromArray($urlTransfer->toArray(), true);

                break;
            }
        }
        $urlTransfer->setFkLocale($localeTransfer->getIdLocale());
        $urlTransfer->setUrlPrefix(
            $this->getLocalizedUrlPrefix($localeTransfer)
        );

        return $urlTransfer;
    }

    /**
     * @param int|null $idMerchant
     *
     * @return array
     */
    public function getOptions(?int $idMerchant = null): array
    {
        return [
            'data_class' => MerchantTransfer::class,
            MerchantCreateForm::OPTION_CURRENT_ID => $idMerchant,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string
     */
    protected function getLocalizedUrlPrefix(LocaleTransfer $localeTransfer): string
    {
        $localeNameParts = explode('_', $localeTransfer->getLocaleName());
        $languageCode = $localeNameParts[0];

        return '/' . $languageCode . '/' . $this->config->getMerchantUrlPrefix() . '/';
    }
}
