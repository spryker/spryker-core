<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Business\Publisher;

use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTranslationTransfer;
use Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper\ConfigurableBundleTemplatePageSearchMapperInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToConfigurableBundleFacadeInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchEntityManagerInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplatePublisher implements ConfigurableBundleTemplatePublisherInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToConfigurableBundleFacadeInterface
     */
    protected $configurableBundleFacade;

    /**
     * @var \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchRepositoryInterface
     */
    protected $configurableBundlePageSearchRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchEntityManagerInterface
     */
    protected $configurableBundlePageSearchEntityManager;

    /**
     * @var \Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper\ConfigurableBundleTemplatePageSearchMapperInterface
     */
    protected $configurableBundleTemplatePageSearchMapper;

    /**
     * @param \Spryker\Zed\ConfigurableBundlePageSearch\Dependency\Facade\ConfigurableBundlePageSearchToConfigurableBundleFacadeInterface $configurableBundleFacade
     * @param \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchRepositoryInterface $configurableBundlePageSearchRepository
     * @param \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchEntityManagerInterface $configurableBundlePageSearchEntityManager
     * @param \Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper\ConfigurableBundleTemplatePageSearchMapperInterface $configurableBundleTemplatePageSearchMapper
     */
    public function __construct(
        ConfigurableBundlePageSearchToConfigurableBundleFacadeInterface $configurableBundleFacade,
        ConfigurableBundlePageSearchRepositoryInterface $configurableBundlePageSearchRepository,
        ConfigurableBundlePageSearchEntityManagerInterface $configurableBundlePageSearchEntityManager,
        ConfigurableBundleTemplatePageSearchMapperInterface $configurableBundleTemplatePageSearchMapper
    ) {
        $this->configurableBundleFacade = $configurableBundleFacade;
        $this->configurableBundlePageSearchRepository = $configurableBundlePageSearchRepository;
        $this->configurableBundlePageSearchEntityManager = $configurableBundlePageSearchEntityManager;
        $this->configurableBundleTemplatePageSearchMapper = $configurableBundleTemplatePageSearchMapper;
    }

    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function publish(array $configurableBundleTemplateIds): void
    {
        $configurableBundleTemplateTransfers = $this->configurableBundleFacade->getConfigurableBundleTemplateCollection(
            (new ConfigurableBundleTemplateFilterTransfer())->setConfigurableBundleTemplateIds($configurableBundleTemplateIds)
        );
        $configurableBundleTemplatePageSearchTransfers = $this->getConfigurableBundleTemplatePageSearchTransfers($configurableBundleTemplateIds);

        $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplateTransfers, $configurableBundleTemplatePageSearchTransfers): void {
            $this->executePublishTransaction($configurableBundleTemplateTransfers, $configurableBundleTemplatePageSearchTransfers);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer[] $configurableBundleTemplateTransfers
     * @param array $groupedConfigurableBundleTemplatePageSearchTransfers
     *
     * @return void
     */
    protected function executePublishTransaction(array $configurableBundleTemplateTransfers, array $groupedConfigurableBundleTemplatePageSearchTransfers): void
    {
        foreach ($configurableBundleTemplateTransfers as $configurableBundleTemplateTransfer) {
            $configurableBundleTemplatePageSearchTransfers = $groupedConfigurableBundleTemplatePageSearchTransfers[$configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate()] ?? [];

            if (!$configurableBundleTemplateTransfer->getIsActive()) {
                if ($configurableBundleTemplatePageSearchTransfers) {
                    $this->deleteConfigurableBundleTemplatePageSearches($configurableBundleTemplatePageSearchTransfers);
                }

                continue;
            }

            $this->storeConfigurableBundlePageSearches(
                $configurableBundleTemplateTransfer,
                $configurableBundleTemplatePageSearchTransfers
            );
        }
    }

    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return array
     */
    protected function getConfigurableBundleTemplatePageSearchTransfers(array $configurableBundleTemplateIds)
    {
        $configurableBundleTemplatePageSearchCollectionTransfer = $this->configurableBundlePageSearchRepository->getConfigurableTemplateBundlePageSearchCollection(
            (new ConfigurableBundleTemplatePageSearchFilterTransfer())->setConfigurableBundleTemplateIds($configurableBundleTemplateIds)
        );

        return $this->groupConfigurableBundleTemplatePageSearchTransfers($configurableBundleTemplatePageSearchCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchCollectionTransfer $configurableBundleTemplatePageSearchCollectionTransfer
     *
     * @return array
     */
    protected function groupConfigurableBundleTemplatePageSearchTransfers(ConfigurableBundleTemplatePageSearchCollectionTransfer $configurableBundleTemplatePageSearchCollectionTransfer): array
    {
        $groupedConfigurableBundleTemplatePageSearchTransfers = [];

        foreach ($configurableBundleTemplatePageSearchCollectionTransfer->getConfigurableBundleTemplatePageSearches() as $configurableBundleTemplatePageSearchTransfer) {
            $fkConfigurableBundleTemplate = $configurableBundleTemplatePageSearchTransfer->getFkConfigurableBundleTemplate();
            $locale = $configurableBundleTemplatePageSearchTransfer->getLocale();

            $groupedConfigurableBundleTemplatePageSearchTransfers[$fkConfigurableBundleTemplate][$locale] = $configurableBundleTemplatePageSearchTransfer;
        }

        return $groupedConfigurableBundleTemplatePageSearchTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer[] $configurableBundleTemplatePageSearchTransfers
     *
     * @return void
     */
    protected function storeConfigurableBundlePageSearches(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer, array $configurableBundleTemplatePageSearchTransfers): void
    {
        $configurableBundleTemplatePageSearchTransfers = $this->getMappedConfigurableBundleTemplatePageSearchTransfers(
            $configurableBundleTemplateTransfer,
            $configurableBundleTemplatePageSearchTransfers
        );

        foreach ($configurableBundleTemplatePageSearchTransfers as $configurableBundleTemplatePageSearchTransfer) {
            $this->storeSingleConfigurableBundlePageSearch($configurableBundleTemplatePageSearchTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return void
     */
    protected function storeSingleConfigurableBundlePageSearch(ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer): void
    {
        if (!$this->validateConfigurableBundleTemplatePageSearchTransfer($configurableBundleTemplatePageSearchTransfer)) {
            $this->configurableBundlePageSearchEntityManager->deleteConfigurableBundlePageSearch($configurableBundleTemplatePageSearchTransfer);

            return;
        }

        if (!$configurableBundleTemplatePageSearchTransfer->getIdConfigurableBundleTemplatePageSearch()) {
            $this->configurableBundlePageSearchEntityManager->createConfigurableBundlePageSearch($configurableBundleTemplatePageSearchTransfer);

            return;
        }

        $this->configurableBundlePageSearchEntityManager->updateConfigurableBundlePageSearch($configurableBundleTemplatePageSearchTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return bool
     */
    protected function validateConfigurableBundleTemplatePageSearchTransfer(ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer): bool
    {
        return $configurableBundleTemplatePageSearchTransfer->getTranslations() &&
            !empty($configurableBundleTemplatePageSearchTransfer->getTranslations()[ConfigurableBundleTemplateTranslationTransfer::NAME]);
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer[] $configurableBundleTemplatePageSearchTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer[]
     */
    protected function getMappedConfigurableBundleTemplatePageSearchTransfers(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer, array $configurableBundleTemplatePageSearchTransfers): array
    {
        $mappedConfigurableBundleTemplatePageSearchTransfers = [];

        foreach ($configurableBundleTemplateTransfer->getTranslations() as $configurableBundleTemplateTranslationTransfer) {
            $configurableBundleTemplateTranslationTransfer->requireLocale()
                ->getLocale()
                ->requireLocaleName();

            $localeName = $configurableBundleTemplateTranslationTransfer->getLocale()->getLocaleName();

            $mappedConfigurableBundleTemplatePageSearchTransfers[] = $this->configurableBundleTemplatePageSearchMapper->mapDataToConfigurableBundleTemplatePageSearchTransfer(
                $configurableBundleTemplateTransfer,
                $configurableBundleTemplateTranslationTransfer,
                $configurableBundleTemplatePageSearchTransfers[$localeName] ?? new ConfigurableBundleTemplatePageSearchTransfer()
            );
        }

        return $mappedConfigurableBundleTemplatePageSearchTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer[] $configurableBundleTemplatePageSearchTransfers
     *
     * @return void
     */
    protected function deleteConfigurableBundleTemplatePageSearches(array $configurableBundleTemplatePageSearchTransfers): void
    {
        foreach ($configurableBundleTemplatePageSearchTransfers as $configurableBundleTemplatePageSearchTransfer) {
            $this->configurableBundlePageSearchEntityManager->deleteConfigurableBundlePageSearch($configurableBundleTemplatePageSearchTransfer);
        }
    }
}
