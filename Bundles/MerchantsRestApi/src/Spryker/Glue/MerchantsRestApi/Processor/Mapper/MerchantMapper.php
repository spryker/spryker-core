<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\RestLegalInformationTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;

class MerchantMapper implements MerchantMapperInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\MerchantRestAttributesMapperPluginInterface[]
     */
    protected $merchantRestAttributesMapperPlugins;

    /**
     * @param \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\MerchantRestAttributesMapperPluginInterface[] $merchantRestAttributesMapperPlugins
     */
    public function __construct(array $merchantRestAttributesMapperPlugins)
    {
        $this->merchantRestAttributesMapperPlugins = $merchantRestAttributesMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    public function mapMerchantStorageTransferToRestMerchantsAttributesTransfer(
        MerchantStorageTransfer $merchantStorageTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        /**
         * @var \Generated\Shared\Transfer\MerchantStorageProfileTransfer $merchantStorageProfileTransfer
         */
        $merchantStorageProfileTransfer = $merchantStorageTransfer->requireMerchantProfile()
            ->getMerchantProfile();

        $restLegalInformationTransfer = (new RestLegalInformationTransfer())
            ->setCancellationPolicy($merchantStorageProfileTransfer->getCancellationPolicy())
            ->setDataPrivacy($merchantStorageProfileTransfer->getDataPrivacy())
            ->setImprint($merchantStorageProfileTransfer->getImprint())
            ->setTerms($merchantStorageProfileTransfer->getTermsConditions());

        $restMerchantsAttributesTransfer->fromArray($merchantStorageTransfer->toArray(), true)
            ->fromArray($merchantStorageProfileTransfer->toArray(), true)
            ->setMerchantName($merchantStorageTransfer->getName())
            ->setLegalInformation($restLegalInformationTransfer)
            ->setBannerUrl($merchantStorageProfileTransfer->getBannerUrl())
            ->setDescription($merchantStorageProfileTransfer->getDescription())
            ->setDeliveryTime($merchantStorageProfileTransfer->getDeliveryTime())
            ->setMerchantUrl($this->findMerchantUrlByLocaleName($merchantStorageTransfer->getUrlCollection(), $localeName));

        $restMerchantsAttributesTransfer = $this->executeMerchantRestAttributesMapperPlugins(
            $merchantStorageTransfer,
            $restMerchantsAttributesTransfer,
            $localeName
        );

        return $restMerchantsAttributesTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\UrlTransfer[] $urlCollection
     * @param string $localeName
     *
     * @return string|null
     */
    protected function findMerchantUrlByLocaleName(ArrayObject $urlCollection, string $localeName): ?string
    {
        foreach ($urlCollection as $urlTransfer) {
            if ($urlTransfer->getLocaleName() === $localeName) {
                return $urlTransfer->getUrl();
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    protected function executeMerchantRestAttributesMapperPlugins(
        MerchantStorageTransfer $merchantStorageTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        foreach ($this->merchantRestAttributesMapperPlugins as $restMerchantAttributesMapperPlugin) {
            $restMerchantsAttributesTransfer = $restMerchantAttributesMapperPlugin->mapMerchantStorageTransferToRestMerchantsAttributesTransfer(
                $merchantStorageTransfer,
                $restMerchantsAttributesTransfer,
                $localeName
            );
        }

        return $restMerchantsAttributesTransfer;
    }
}
