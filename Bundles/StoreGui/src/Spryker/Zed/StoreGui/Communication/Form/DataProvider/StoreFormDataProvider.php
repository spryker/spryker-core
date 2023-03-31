<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface;

class StoreFormDataProvider
{
    /**
     * @var \Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\StoreGui\Dependency\Facade\StoreGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(StoreGuiToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param int|null $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getData(?int $idStore = null): StoreTransfer
    {
        $storeTransfer = new StoreTransfer();

        if ($idStore) {
            $storeTransfer = $this->storeFacade->getStoreById($idStore);
        }

        return $storeTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => StoreTransfer::class,
        ];
    }
}
