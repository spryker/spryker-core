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
     * @var \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\RestStorageMerchantsAttributesMapperPluginInterface[]
     */
    protected $restStorageMerchantsAttributesMapperPlugins;

    /**
     * @var \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\RestSearchMerchantsAttributesMapperPluginInterface[]
     */
    protected $restSearchMerchantsAttributesMapperPlugins;

    /**
     * @param \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\RestStorageMerchantsAttributesMapperPluginInterface[] $restStorageMerchantsAttributesMapperPlugins
     * @param \Spryker\Glue\MerchantsRestApiExtension\Dependency\Plugin\RestSearchMerchantsAttributesMapperPluginInterface[] $restSearchMerchantsAttributesMapperPlugins
     */
    public function __construct(
        array $restStorageMerchantsAttributesMapperPlugins,
        array $restSearchMerchantsAttributesMapperPlugins
    ) {
        $this->restStorageMerchantsAttributesMapperPlugins = $restStorageMerchantsAttributesMapperPlugins;
        $this->restSearchMerchantsAttributesMapperPlugins = $restSearchMerchantsAttributesMapperPlugins;
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

        $restMerchantsAttributesTransfer = $this->executeRestStorageMerchantsAttributesMapperPlugins(
            $merchantStorageTransfer,
            $restMerchantsAttributesTransfer,
            $localeName
        );

        return $restMerchantsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    public function mapMerchantSearchTransferToRestMerchantsAttributesTransfer(
        MerchantSearchTransfer $merchantSearchTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        $merchantProfileTransfer = $merchantSearchTransfer->getMerchantProfile();

        $restLegalInformationTransfer = (new RestLegalInformationTransfer())
            ->setCancellationPolicy($merchantProfileTransfer->getCancellationPolicy())
            ->setDataPrivacy($merchantProfileTransfer->getDataPrivacy())
            ->setImprint($merchantProfileTransfer->getImprint())
            ->setTerms($merchantProfileTransfer->getTermsConditions());

        $restMerchantsAttributesTransfer->fromArray($merchantSearchTransfer->toArray(), true);
        $restMerchantsAttributesTransfer->setMerchantName($merchantSearchTransfer->getName())
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

        $restMerchantsAttributesTransfer = $this->executeRestSearchMerchantsAttributesMapperPlugins(
            $merchantSearchTransfer,
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
    protected function executeRestStorageMerchantsAttributesMapperPlugins(
        MerchantStorageTransfer $merchantStorageTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        foreach ($this->restStorageMerchantsAttributesMapperPlugins as $restStorageMerchantAttributesMapperPlugin) {
            $restMerchantsAttributesTransfer = $restStorageMerchantAttributesMapperPlugin->mapMerchantStorageTransferToRestMerchantsAttributesTransfer(
                $merchantStorageTransfer,
                $restMerchantsAttributesTransfer,
                $localeName
            );
        }

        return $restMerchantsAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer
     * @param \Generated\Shared\Transfer\RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestMerchantsAttributesTransfer
     */
    protected function executeRestSearchMerchantsAttributesMapperPlugins(
        MerchantSearchTransfer $merchantSearchTransfer,
        RestMerchantsAttributesTransfer $restMerchantsAttributesTransfer,
        string $localeName
    ): RestMerchantsAttributesTransfer {
        foreach ($this->restSearchMerchantsAttributesMapperPlugins as $restSearchMerchantsAttributesMapperPlugin) {
            $restMerchantsAttributesTransfer = $restSearchMerchantsAttributesMapperPlugin->mapMerchantSearchTransferToRestMerchantsAttributesTransfer(
                $merchantSearchTransfer,
                $restMerchantsAttributesTransfer,
                $localeName
            );
        }

        return $restMerchantsAttributesTransfer;
    }
}
