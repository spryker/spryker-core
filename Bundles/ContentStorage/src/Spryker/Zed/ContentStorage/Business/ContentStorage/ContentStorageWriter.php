<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Business\ContentStorage;

use Generated\Shared\Transfer\ContentStorageTransfer;
use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Shared\ContentStorage\ContentStorageConfig;
use Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToLocaleFacadeInterface;
use Spryker\Zed\ContentStorage\Dependency\Service\ContentStorageToUtilEncodingInterface;
use Spryker\Zed\ContentStorage\Persistence\ContentStorageEntityManagerInterface;
use Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ContentStorageWriter implements ContentStorageWriterInterface
{
    use TransactionTrait;

    protected const DEFAULT_LOCALE = 'DEFAULT_LOCALE';

    /**
     * @var \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface
     */
    protected $contentStorageRepository;

    /**
     * @var \Spryker\Zed\ContentStorage\Persistence\ContentStorageEntityManagerInterface
     */
    protected $contentStorageEntityManager;

    /**
     * @var \Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ContentStorage\Dependency\Service\ContentStorageToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface $contentStorageRepository
     * @param \Spryker\Zed\ContentStorage\Persistence\ContentStorageEntityManagerInterface $contentStorageEntityManager
     * @param \Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ContentStorage\Dependency\Service\ContentStorageToUtilEncodingInterface $utilEncodingService
     */
    public function __construct(
        ContentStorageRepositoryInterface $contentStorageRepository,
        ContentStorageEntityManagerInterface $contentStorageEntityManager,
        ContentStorageToLocaleFacadeInterface $localeFacade,
        ContentStorageToUtilEncodingInterface $utilEncodingService
    ) {
        $this->contentStorageRepository = $contentStorageRepository;
        $this->contentStorageEntityManager = $contentStorageEntityManager;
        $this->localeFacade = $localeFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param int[] $contentIds
     *
     * @return void
     */
    public function publish(array $contentIds): void
    {
        $contentTransfers = $this->contentStorageRepository->findContentByIds($contentIds);
        $contentStorageTransfers = $this->contentStorageRepository->findContentStorageByContentIds($contentIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($contentTransfers, $contentStorageTransfers) {
            return $this->executePublishTransaction($contentTransfers, $contentStorageTransfers);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer[] $contentTransfers
     * @param \Generated\Shared\Transfer\ContentStorageTransfer[] $contentStorageTransfers
     *
     * @return bool
     */
    protected function executePublishTransaction(iterable $contentTransfers, iterable $contentStorageTransfers): bool
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();
        $contentStorageTransfers = $this->groupByIdContentAndLocale($contentStorageTransfers);
        foreach ($contentTransfers as $contentTransfer) {
            $this->saveContentStorage($contentTransfer, $contentStorageTransfers, $availableLocales);
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     * @param array $contentStorageTransfers
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $availableLocales
     *
     * @return void
     */
    protected function saveContentStorage(ContentTransfer $contentTransfer, array $contentStorageTransfers, array $availableLocales): void
    {
        $localizedContentTransfers = $this->indexContentTransfersByLocale($contentTransfer);

        foreach ($availableLocales as $availableLocale) {
            $localizedContentTransfer = $localizedContentTransfers[$availableLocale->getLocaleName()] ?? $localizedContentTransfers[static::DEFAULT_LOCALE];

            $contentStorageTransfer = new ContentStorageTransfer();
            if (!empty($contentStorageTransfers[$contentTransfer->getIdContent()][$availableLocale->getLocaleName()])) {
                $contentStorageTransfer->fromArray(
                    $contentStorageTransfers[$contentTransfer->getIdContent()][$availableLocale->getLocaleName()]->toArray()
                );
            }

            $contentStorageTransfer->setFkContent($contentTransfer->getIdContent())
                ->setContentKey($contentTransfer->getKey())
                ->setLocale($availableLocale->getLocaleName())
                ->setData($this->utilEncodingService->encodeJson([
                    ContentStorageConfig::ID_CONTENT => $contentTransfer->getIdContent(),
                    ContentStorageConfig::TERM_KEY => $contentTransfer->getContentTermKey(),
                    ContentStorageConfig::CONTENT_KEY => $this->utilEncodingService->decodeJson($localizedContentTransfer->getParameters(), true),
                ]));

            $this->contentStorageEntityManager->saveContentStorageEntity($contentStorageTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return array
     */
    protected function indexContentTransfersByLocale(ContentTransfer $contentTransfer): array
    {
        $localizedContentTransfers = [];

        foreach ($contentTransfer->getLocalizedContents() as $localizedContentTransfer) {
            $localeKey = $localizedContentTransfer->getFkLocale() ? $localizedContentTransfer->getLocaleName() : static::DEFAULT_LOCALE;
            $localizedContentTransfers[$localeKey] = $localizedContentTransfer;
        }

        return $localizedContentTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentStorageTransfer[] $contentStorageTransfers
     *
     * @return array
     */
    protected function groupByIdContentAndLocale(iterable $contentStorageTransfers): array
    {
        $contentStorageList = [];

        foreach ($contentStorageTransfers as $contentStorageTransfer) {
            $contentStorageList[$contentStorageTransfer->getFkContent()][$contentStorageTransfer->getLocale()] = $contentStorageTransfer;
        }

        return $contentStorageList;
    }
}
