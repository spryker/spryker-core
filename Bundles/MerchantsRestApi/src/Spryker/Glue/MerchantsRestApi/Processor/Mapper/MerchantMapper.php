<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\MerchantSearchTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\RestLegalInformationTransfer;
use Generated\Shared\Transfer\RestMerchantsAttributesTransfer;

class MerchantMapper implements MerchantMapperInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\RestMerchantsAttributesMapperPluginInterface[]
     */
    protected $restMerchantsAttributesMapperPlugins;

    /**
     * @param \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\RestMerchantsAttributesMapperPluginInterface[] $restMerchantsAttributesMapperPlugins
     */
    public function __construct(array $restMerchantsAttributesMapperPlugins)
    {
        $this->restMerchantsAttributesMapperPlugins = $restMerchantsAttributesMapperPlugins;
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
            ->setMerchantUrl($this->findMerchantUrlByLocaleName($merchantStorageTransfer->getUrlCollection(), $localeName));

        $restMerchantsAttributesTransfer = $this->executeRestMerchantsAttributesMapperPlugins(
            $merchantStorageTransfer,
            $restMerchantsAttributesTransfer,
            $localeName
        );

        return $restMerchantsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTrasnsfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    public function mapMerchantSearchTransferToRestMerchantsAttributesTransfer(
        MerchantSearchTransfer $merchantSearchTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTrasnsfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        $merchantProfileTransfer = $merchantSearchTransfer->getMerchantProfile();

        $restLegalInformationTransfer = (new RestLegalInformationTransfer())
            ->setCancellationPolicy($merchantProfileTransfer->getCancellationPolicy())
            ->setDataPrivacy($merchantProfileTransfer->getDataPrivacy())
            ->setImprint($merchantProfileTransfer->getImprint())
            ->setTerms($merchantProfileTransfer->getTermsConditions());

        $restMerchantsAttributesTrasnsfer->fromArray($merchantSearchTransfer->toArray(), true);
        $restMerchantsAttributesTrasnsfer->setMerchantName($merchantSearchTransfer->getName())
            ->setMerchantUrl($this->findMerchantUrlByLocaleName($merchantSearchTransfer->getUrlCollection(), $localeName))
            ->setContactPersonRole($merchantProfileTransfer->getContactPersonRole())
            ->setContactPersonTitle($merchantProfileTransfer->getContactPersonTitle())
            ->setContactPersonFirstName($merchantProfileTransfer->getContactPersonFirstName())
            ->setContactPersonLastName($merchantProfileTransfer->getContactPersonLastName())
            ->setContactPersonPhone($merchantProfileTransfer->getContactPersonPhone())
            ->setLogoUrl($merchantProfileTransfer->getLogoUrl())
            ->setPublicEmail($merchantProfileTransfer->getPublicEmail())
            ->setPublicPhone($merchantProfileTransfer->getPublicPhone())
            ->setDescription($merchantProfileTransfer->getDescription())
            ->setBannerUrl($merchantProfileTransfer->getBannerUrl())
            ->setDeliveryTime($merchantProfileTransfer->getDeliveryTime())
            ->setLatitude($merchantProfileTransfer->getLatitude())
            ->setLongitude($merchantProfileTransfer->getLongitude())
            ->setFaxNumber($merchantProfileTransfer->getFaxNumber())
            ->setLegalInformation($restLegalInformationTransfer);

        return $restMerchantsAttributesTrasnsfer;
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
    protected function executeRestMerchantsAttributesMapperPlugins(
        MerchantStorageTransfer $merchantStorageTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        foreach ($this->restMerchantsAttributesMapperPlugins as $restMerchantAttributesMapperPlugin) {
            $restMerchantsAttributesTransfer = $restMerchantAttributesMapperPlugin->mapMerchantStorageTransferToRestMerchantsAttributesTransfer(
                $merchantStorageTransfer,
                $restMerchantsAttributesTransfer,
                $localeName
            );
        }

        return $restMerchantsAttributesTransfer;
    }
}
