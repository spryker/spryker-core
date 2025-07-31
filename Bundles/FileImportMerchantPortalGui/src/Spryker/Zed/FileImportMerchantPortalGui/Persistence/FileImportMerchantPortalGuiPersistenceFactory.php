<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Persistence;

use Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery;
use Spryker\Zed\FileImportMerchantPortalGui\Persistence\Propel\Mapper\MerchantFileImportMapper;
use Spryker\Zed\FileImportMerchantPortalGui\Persistence\Propel\Mapper\MerchantFileImportTableDataMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\Persistence\FileImportMerchantPortalGuiEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\FileImportMerchantPortalGui\FileImportMerchantPortalGuiConfig getConfig()
 */
class FileImportMerchantPortalGuiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImportQuery
     */
    public function createMerchantFileImportQuery(): SpyMerchantFileImportQuery
    {
        return SpyMerchantFileImportQuery::create();
    }

    /**
     * @return \Spryker\Zed\FileImportMerchantPortalGui\Persistence\Propel\Mapper\MerchantFileImportMapper
     */
    public function createMerchantFileImportMapper(): MerchantFileImportMapper
    {
        return new MerchantFileImportMapper();
    }

    /**
     * @return \Spryker\Zed\FileImportMerchantPortalGui\Persistence\Propel\Mapper\MerchantFileImportTableDataMapper
     */
    public function createMerchantFileImportTableDataMapper(): MerchantFileImportTableDataMapper
    {
        return new MerchantFileImportTableDataMapper();
    }
}
