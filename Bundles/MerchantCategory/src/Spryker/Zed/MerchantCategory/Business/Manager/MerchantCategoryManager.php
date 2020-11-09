<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Business\Manager;

use Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryEntityManagerInterface;

class MerchantCategoryManager implements MerchantCategoryManagerInterface
{
    /**
     * @var \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryEntityManagerInterface $entityManager
     */
    public function __construct(
        MerchantCategoryEntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function removeMappings(int $idCategory): void
    {
        $this->entityManager->deleteAllByFkCategory($idCategory);
    }
}
