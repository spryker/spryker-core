<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Business\Storage;

class CompanyUserStorageWriter implements CompanyUserStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface
     */
    protected $companyUserStorageRepository;

    /**
     * @var \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageEntityManagerInterface
     */
    protected $companyUserStorageEntityManager;

    /**
     * @param array $companyUserIds
     *
     * @return void
     */
    public function publish(array $companyUserIds): void
    {
        // TODO: Implement publish() method.
    }

    /**
     * @param array $companyUserIds
     *
     * @return void
     */
    public function unpublish(array $companyUserIds): void
    {
        // TODO: Implement unpublish() method.
    }
}
