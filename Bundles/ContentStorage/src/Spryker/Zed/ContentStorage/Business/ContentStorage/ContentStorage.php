<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Business\ContentStorage;

use ArrayObject;
use Generated\Shared\Transfer\ContentStorageTransfer;
use Generated\Shared\Transfer\ContentTransfer;
use Spryker\Shared\ContentStorage\ContentStorageConstants;
use Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToLocaleFacadeInterface;
use Spryker\Zed\ContentStorage\Persistence\ContentStorageEntityManagerInterface;
use Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ContentStorage implements ContentStorageInterface
{
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

    use TransactionTrait;
    use \Spryker\Shared\Log\LoggerTrait;

    /**
     * @param \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface $contentStorageRepository
     * @param \Spryker\Zed\ContentStorage\Persistence\ContentStorageEntityManagerInterface $contentStorageEntityManager
     * @param \Spryker\Zed\ContentStorage\Dependency\Facade\ContentStorageToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ContentStorageRepositoryInterface $contentStorageRepository,
        ContentStorageEntityManagerInterface $contentStorageEntityManager,
        ContentStorageToLocaleFacadeInterface $localeFacade
    ) {
        $this->contentStorageRepository = $contentStorageRepository;
        $this->contentStorageEntityManager = $contentStorageEntityManager;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param array $contentIds
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
     * @param \ArrayObject|\Generated\Shared\Transfer\ContentTransfer[] $contentTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\ContentStorageTransfer[] $contentStorageTransfers
     *
     * @return bool
     */
    protected function executePublishTransaction(ArrayObject $contentTransfers, ArrayObject $contentStorageTransfers): bool
    {
        $availableLocales = $this->localeFacade->getLocaleCollection();
        $contentStorageTransfers = $this->structureContentStorage($contentStorageTransfers);
        foreach ($contentTransfers as $contentTransfer) {
            $localizedContents = $this->getExtractLocalizedContents($contentTransfer);

            foreach ($availableLocales as $availableLocale) {
                $localizedContent = (!empty($localizedContents[$availableLocale->getLocaleName()])) ?
                    $localizedContents[$availableLocale->getLocaleName()] :
                    $localizedContents[static::DEFAULT_LOCALE];
                $this->getLogger()->info(serialize($localizedContent));
                $contentStorageTransfer = new ContentStorageTransfer();

                if (!empty($contentStorageTransfers[$contentTransfer->getIdContent()][$availableLocale->getLocaleName()])) {
                    $contentStorageTransfer->fromArray(
                        $contentStorageTransfers[$contentTransfer->getIdContent()][$availableLocale->getLocaleName()]->toArray()
                    );
                }

                $contentStorageTransfer->setFkContent($contentTransfer->getIdContent());
                $contentStorageTransfer->setLocale($availableLocale->getLocaleName());
                $contentStorageTransfer->setData([
                    ContentStorageConstants::TERM_KEY => $contentTransfer->getContentTermCandidateKey(),
                    ContentStorageConstants::CONTENT_KEY => json_decode($localizedContent->getParameters(), true),
                ]);

                $this->contentStorageEntityManager->saveContentStorageEntity($contentStorageTransfer);
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ContentTransfer $contentTransfer
     *
     * @return array
     */
    protected function getExtractLocalizedContents(ContentTransfer $contentTransfer)
    {
        $localizedContents = [];

        foreach ($contentTransfer->getLocalizedContents() as $contentLocalized) {
            $localeKey = ($contentLocalized->getFkLocale() === null) ?
                static::DEFAULT_LOCALE :
                $contentLocalized->getLocaleName();

            $localizedContents[$localeKey] = $contentLocalized;
        }

        return $localizedContents;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ContentStorageTransfer[] $contentStorageTransfers
     *
     * @return array
     */
    protected function structureContentStorage(ArrayObject $contentStorageTransfers)
    {
        $contentStorageList = [];

        foreach ($contentStorageTransfers as $contentStorageTransfer) {
            $contentStorageList[$contentStorageTransfer->getFkContent()][$contentStorageTransfer->getLocale()] = $contentStorageTransfer;
        }

        return $contentStorageList;
    }
}
