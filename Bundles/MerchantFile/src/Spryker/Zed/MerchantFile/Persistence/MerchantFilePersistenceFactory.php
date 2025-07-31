<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Persistence;

use Orm\Zed\MerchantFile\Persistence\SpyMerchantFileQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantFile\Persistence\Propel\Mapper\MerchantFileMapper;

/**
 * @method \Spryker\Zed\MerchantFile\Persistence\MerchantFileRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantFile\Persistence\MerchantFileEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantFile\MerchantFileConfig getConfig()
 */
class MerchantFilePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantFile\Persistence\SpyMerchantFileQuery
     */
    public function createMerchantFileQuery(): SpyMerchantFileQuery
    {
        return SpyMerchantFileQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantFile\Persistence\Propel\Mapper\MerchantFileMapper
     */
    public function createMerchantFileMapper(): MerchantFileMapper
    {
        return new MerchantFileMapper();
    }
}
