<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Reader;

use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListCriteriaTransfer;
use Spryker\Zed\PickingList\Business\Expander\PickingListExpanderInterface;
use Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface;

class PickingListReader implements PickingListReaderInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface
     */
    protected PickingListRepositoryInterface $pickingListRepository;

    /**
     * @var \Spryker\Zed\PickingList\Business\Expander\PickingListExpanderInterface
     */
    protected PickingListExpanderInterface $pickingListExpander;

    /**
     * @var list<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListCollectionExpanderPluginInterface>
     */
    protected array $pickingListCollectionExpanderPlugins;

    /**
     * @param \Spryker\Zed\PickingList\Persistence\PickingListRepositoryInterface $pickingListRepository
     * @param \Spryker\Zed\PickingList\Business\Expander\PickingListExpanderInterface $pickingListExpander
     * @param array<\Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListCollectionExpanderPluginInterface> $pickingListCollectionExpanderPlugins
     */
    public function __construct(
        PickingListRepositoryInterface $pickingListRepository,
        PickingListExpanderInterface $pickingListExpander,
        array $pickingListCollectionExpanderPlugins
    ) {
        $this->pickingListRepository = $pickingListRepository;
        $this->pickingListExpander = $pickingListExpander;
        $this->pickingListCollectionExpanderPlugins = $pickingListCollectionExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCriteriaTransfer $pickingListCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function getPickingListCollection(
        PickingListCriteriaTransfer $pickingListCriteriaTransfer
    ): PickingListCollectionTransfer {
        $pickingListCollectionTransfer = $this->pickingListRepository->getPickingListCollection($pickingListCriteriaTransfer);
        $pickingListCollectionTransfer = $this->pickingListExpander->expandPickingListCollectionWithOrderItems(
            $pickingListCollectionTransfer,
        );

        return $this->executePickingListCollectionExpanderPlugins($pickingListCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    protected function executePickingListCollectionExpanderPlugins(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer {
        foreach ($this->pickingListCollectionExpanderPlugins as $pickingListCollectionExpanderPlugin) {
            $pickingListCollectionTransfer = $pickingListCollectionExpanderPlugin->expand($pickingListCollectionTransfer);
        }

        return $pickingListCollectionTransfer;
    }
}
