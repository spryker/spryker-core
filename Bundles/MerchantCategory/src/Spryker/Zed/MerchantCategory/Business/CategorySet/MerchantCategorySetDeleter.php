<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCategory\Business\CategorySet;

use Spryker\Zed\MerchantCategory\Persistence\MerchantCategoryEntityManagerInterface;

class MerchantCategorySetDeleter implements MerchantCategorySetDeleterInterface
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
    public function deleteMerchantCategorySetsByIdCategory(int $idCategory): void
    {
        $this->entityManager->deleteAllByFkCategory($idCategory);
    }
}
