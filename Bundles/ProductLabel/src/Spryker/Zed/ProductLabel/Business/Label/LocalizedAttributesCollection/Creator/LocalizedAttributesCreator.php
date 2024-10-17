<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label\LocalizedAttributesCollection\Creator;

use Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToStoreFacadeInterface;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class LocalizedAttributesCreator implements LocalizedAttributesCreatorInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToStoreFacadeInterface
     */
    protected ProductLabelToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface
     */
    protected ProductLabelEntityManagerInterface $entityManager;

    /**
     * @param \Spryker\Zed\ProductLabel\Dependency\Facade\ProductLabelToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelEntityManagerInterface $entityManager
     */
    public function __construct(
        ProductLabelToStoreFacadeInterface $storeFacade,
        ProductLabelEntityManagerInterface $entityManager
    ) {
        $this->storeFacade = $storeFacade;
        $this->entityManager = $entityManager;
    }

    /**
     * @return void
     */
    public function createMissingLocalizedAttributes(): void
    {
        if ($this->storeFacade->isDynamicStoreEnabled() !== true) {
            return;
        }

        $this->handleDatabaseTransaction(function () {
            $this->entityManager->createMissingLocalizedAttributes();
        });
    }
}
