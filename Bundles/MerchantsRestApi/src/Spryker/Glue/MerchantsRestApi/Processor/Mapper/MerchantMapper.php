<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\RestLegalInformationTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;

class MerchantMapper implements MerchantMapperInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\RestMerchantAttributesMapperPluginInterface[]
     */
    protected $restMerchantAttributesMapperPlugins;

    /**
     * @param \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\RestMerchantAttributesMapperPluginInterface[] $restMerchantAttributesMapperPlugins
     */
    public function __construct(array $restMerchantAttributesMapperPlugins)
    {
        $this->restMerchantAttributesMapperPlugins = $restMerchantAttributesMapperPlugins;
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
        $merchantStorageProfileTransfer = $merchantStorageTransfer->getMerchantProfile();

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
            ->setMerchantUrl($this->findMerchantUrlByLocaleName($merchantStorageTransfer, $localeName));

        $restMerchantsAttributesTransfer = $this->executeRestMerchantAttributesMapperPlugins(
            $merchantStorageTransfer,
            $restMerchantsAttributesTransfer,
            $localeName
        );

        return $restMerchantsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param string $localeName
     *
     * @return string|null
     */
    protected function findMerchantUrlByLocaleName(MerchantStorageTransfer $merchantStorageTransfer, string $localeName): ?string
    {
        foreach ($merchantStorageTransfer->getUrlCollection() as $urlTransfer) {
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
    protected function executeRestMerchantAttributesMapperPlugins(
        MerchantStorageTransfer $merchantStorageTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        foreach ($this->restMerchantAttributesMapperPlugins as $restMerchantAttributesMapperPlugin) {
            $restMerchantsAttributesTransfer = $restMerchantAttributesMapperPlugin->mapMerchantStorageTransferToRestMerchantsAttributesTransfer(
                $merchantStorageTransfer,
                $restMerchantsAttributesTransfer,
                $localeName
            );
        }

        return $restMerchantsAttributesTransfer;
    }
}
