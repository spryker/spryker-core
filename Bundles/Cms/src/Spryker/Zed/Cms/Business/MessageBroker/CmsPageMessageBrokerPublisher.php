<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Zed\Cms\Business\MessageBroker;

use Generated\Shared\Transfer\CmsPageMessageBrokerRequestTransfer;
use Generated\Shared\Transfer\CmsPagePublishedTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsPageUnpublishedTransfer;
use Generated\Shared\Transfer\CmsVersionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\Cms\Business\Extractor\DataExtractorInterface;
use Spryker\Zed\Cms\Business\Page\CmsPageReaderInterface;
use Spryker\Zed\Cms\Business\Page\LocaleCmsPageDataExpanderInterface;
use Spryker\Zed\Cms\Business\Version\VersionFinderInterface;
use Spryker\Zed\Cms\CmsConfig;
use Spryker\Zed\Cms\Dependency\Facade\CmsToMessageBrokerFacadeInterface;
use Spryker\Zed\Cms\Persistence\CmsRepositoryInterface;

class CmsPageMessageBrokerPublisher implements CmsPageMessageBrokerPublisherInterface
{
    /**
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToMessageBrokerFacadeInterface $messageBrokerFacade
     * @param \Spryker\Zed\Cms\Business\Page\CmsPageReaderInterface $cmsPageReader
     * @param \Spryker\Zed\Cms\Persistence\CmsRepositoryInterface $cmsRepository
     * @param \Spryker\Zed\Cms\Business\Version\VersionFinderInterface $versionFinder
     * @param \Spryker\Zed\Cms\Business\Extractor\DataExtractorInterface $dataExtractor
     * @param \Spryker\Zed\Cms\Business\Page\LocaleCmsPageDataExpanderInterface $localeCmsPageDataExpander
     * @param \Spryker\Zed\Cms\CmsConfig $cmsConfig
     */
    public function __construct(
        protected CmsToMessageBrokerFacadeInterface $messageBrokerFacade,
        protected CmsPageReaderInterface $cmsPageReader,
        protected CmsRepositoryInterface $cmsRepository,
        protected VersionFinderInterface $versionFinder,
        protected DataExtractorInterface $dataExtractor,
        protected LocaleCmsPageDataExpanderInterface $localeCmsPageDataExpander,
        protected CmsConfig $cmsConfig
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageMessageBrokerRequestTransfer $cmsPageMessageBrokerRequestTransfer
     *
     * @return void
     */
    public function sendCmsPagesToMessageBroker(CmsPageMessageBrokerRequestTransfer $cmsPageMessageBrokerRequestTransfer): void
    {
        $cmsPageIds = $cmsPageMessageBrokerRequestTransfer->getCmsPageIds();
        $cmsVersionIds = $cmsPageMessageBrokerRequestTransfer->getCmsVersionIds();

        if ($cmsVersionIds) {
            $this->handleCmsPagePublishedMessages($cmsVersionIds);

            return;
        }

        $this->handleCmsPageUpdatedMessages($cmsPageIds);
    }

    /**
     * @param array<int> $cmsVersionIds
     *
     * @return void
     */
    protected function handleCmsPagePublishedMessages(array $cmsVersionIds): void
    {
        $cmsVersionTransfers = $this->versionFinder->findCmsVersionsByIds($cmsVersionIds);
        if ($cmsVersionTransfers->count() === 0) {
            return;
        }

        foreach ($cmsVersionTransfers as $cmsVersionTransfer) {
            $cmsPageTransfer = $this->getCmsPageTransfer($cmsVersionTransfer->getFkCmsPage());

            if (!$cmsPageTransfer) {
                continue;
            }

            $cmsPagePublishedTransfer = $this->createCmsPagePublishedTransfer($cmsPageTransfer, $cmsVersionTransfer);
            $this->messageBrokerFacade->sendMessage($cmsPagePublishedTransfer);
        }
    }

    /**
     * @param array<int> $cmsPageIds
     *
     * @return void
     */
    protected function handleCmsPageUpdatedMessages(array $cmsPageIds): void
    {
        if (!$cmsPageIds) {
            return;
        }

        foreach ($cmsPageIds as $cmsPageId) {
            $cmsPageTransfer = $this->getCmsPageTransfer($cmsPageId);
            if ($cmsPageTransfer === null) {
                continue;
            }

            if ($cmsPageTransfer->getIsActive() && $cmsPageTransfer->getIsSearchable()) {
                $cmsVersionTransfer = $this->versionFinder->findLatestCmsVersionByIdCmsPage($cmsPageId);
                $cmsPageUpdatedTransfer = $this->createCmsPagePublishedTransfer($cmsPageTransfer, $cmsVersionTransfer);
            } else {
                $cmsPageUpdatedTransfer = $this->createCmsPageUnpublishedTransfer($cmsPageId);
            }

            $this->messageBrokerFacade->sendMessage($cmsPageUpdatedTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param \Generated\Shared\Transfer\CmsVersionTransfer $cmsVersionTransfer
     *
     * @return \Generated\Shared\Transfer\CmsPagePublishedTransfer
     */
    protected function createCmsPagePublishedTransfer(CmsPageTransfer $cmsPageTransfer, CmsVersionTransfer $cmsVersionTransfer): CmsPagePublishedTransfer
    {
        $cmsPagePublishedTransfer = new CmsPagePublishedTransfer();
        $cmsPagePublishedTransfer->setId($cmsPageTransfer->getFkPage());
        $cmsPagePublishedTransfer->setCmsPage($cmsPageTransfer);
        $cmsPagePublishedTransfer->setCreatedAt($this->getCmsPageCreatedAt($cmsPageTransfer->getFkPage()));
        $cmsPagePublishedTransfer->setUpdatedAt($cmsVersionTransfer->getCreatedAt());
        $cmsPagePublishedTransfer->setFlattenedLocaleCmsPageDatum($this->getFlattenedLocaleCmsPageDatum($cmsPageTransfer));
        $cmsPagePublishedTransfer->setMessageAttributes($this->createMessageAttributesTransfer());

        return $cmsPagePublishedTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    protected function createMessageAttributesTransfer(): MessageAttributesTransfer
    {
        $tenantIdentifier = $this->cmsConfig->getTenantIdentifier();
        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->setStoreReference($tenantIdentifier);
        $messageAttributesTransfer->setTenantIdentifier($tenantIdentifier);

        return $messageAttributesTransfer;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsPageUnpublishedTransfer
     */
    protected function createCmsPageUnpublishedTransfer(int $idCmsPage): CmsPageUnpublishedTransfer
    {
        $cmsPageUnpublishedTransfer = new CmsPageUnpublishedTransfer();
        $cmsPageUnpublishedTransfer->setId($idCmsPage);
        $cmsPageUnpublishedTransfer->setMessageAttributes($this->createMessageAttributesTransfer());

        return $cmsPageUnpublishedTransfer;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer|null
     */
    protected function getCmsPageTransfer(int $idCmsPage): ?CmsPageTransfer
    {
        return $this->cmsPageReader->findCmsPageById($idCmsPage);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return array<string, array>
     */
    protected function getFlattenedLocaleCmsPageDatum(CmsPageTransfer $cmsPageTransfer): array
    {
        $localeCmsPageData = [];
        $cmsVersionDataTransfer = $this->versionFinder
            ->getCmsVersionData($cmsPageTransfer->getFkPage());

        foreach ($cmsPageTransfer->getPageAttributes() as $pageAttribute) {
            $localeTransfer = (new LocaleTransfer())->setLocaleName($pageAttribute->getLocaleName())->setIdLocale($pageAttribute->getFkLocale());

            $localeCmsPageDataTransfer = $this->dataExtractor->extractLocaleCmsPageDataTransfer($cmsVersionDataTransfer, $localeTransfer);
            $flattenedLocaleCmsPageData = $this->localeCmsPageDataExpander->calculateFlattenedLocaleCmsPageData($localeCmsPageDataTransfer, $localeTransfer);

            $localeCmsPageData[$pageAttribute->getLocaleName()] = $flattenedLocaleCmsPageData;
        }

        return $localeCmsPageData;
    }

    /**
     * @param int $idCmsPage
     *
     * @return string|null
     */
    protected function getCmsPageCreatedAt(int $idCmsPage): ?string
    {
        $firstCmsVersionTransfer = $this->versionFinder->findCmsVersionByIdCmsPageAndVersion($idCmsPage, 1);
        if ($firstCmsVersionTransfer === null) {
            return null;
        }

        return $firstCmsVersionTransfer->getCreatedAt();
    }
}
