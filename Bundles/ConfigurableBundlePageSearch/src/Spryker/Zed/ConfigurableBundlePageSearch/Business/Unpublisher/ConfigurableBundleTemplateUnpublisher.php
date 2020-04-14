<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Business\Unpublisher;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchCollectionTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchFilterTransfer;
use Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchEntityManagerInterface;
use Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ConfigurableBundleTemplateUnpublisher implements ConfigurableBundleTemplateUnpublisherInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchRepositoryInterface
     */
    protected $configurableBundlePageSearchRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchEntityManagerInterface
     */
    protected $configurableBundlePageSearchEntityManager;

    /**
     * @param \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchRepositoryInterface $configurableBundlePageSearchRepository
     * @param \Spryker\Zed\ConfigurableBundlePageSearch\Persistence\ConfigurableBundlePageSearchEntityManagerInterface $configurableBundlePageSearchEntityManager
     */
    public function __construct(
        ConfigurableBundlePageSearchRepositoryInterface $configurableBundlePageSearchRepository,
        ConfigurableBundlePageSearchEntityManagerInterface $configurableBundlePageSearchEntityManager
    ) {
        $this->configurableBundlePageSearchRepository = $configurableBundlePageSearchRepository;
        $this->configurableBundlePageSearchEntityManager = $configurableBundlePageSearchEntityManager;
    }

    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return void
     */
    public function unpublish(array $configurableBundleTemplateIds): void
    {
        $configurableBundleTemplatePageSearchCollectionTransfer = $this->configurableBundlePageSearchRepository->getConfigurableBundleTemplatePageSearchCollection(
            (new ConfigurableBundleTemplatePageSearchFilterTransfer())->setConfigurableBundleTemplateIds($configurableBundleTemplateIds)
        );

        $this->getTransactionHandler()->handleTransaction(function () use ($configurableBundleTemplatePageSearchCollectionTransfer): void {
            $this->executeUnpublishTransaction($configurableBundleTemplatePageSearchCollectionTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchCollectionTransfer $configurableBundleTemplatePageSearchCollectionTransfer
     *
     * @return void
     */
    protected function executeUnpublishTransaction(
        ConfigurableBundleTemplatePageSearchCollectionTransfer $configurableBundleTemplatePageSearchCollectionTransfer
    ): void {
        foreach ($configurableBundleTemplatePageSearchCollectionTransfer->getConfigurableBundleTemplatePageSearches() as $configurableBundleTemplatePageSearchTransfer) {
            $this->configurableBundlePageSearchEntityManager->deleteConfigurableBundlePageSearch($configurableBundleTemplatePageSearchTransfer);
        }
    }
}
