<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Persistence;

use Orm\Zed\QuoteApproval\Persistence\SpyQuoteApprovalQuery;
use Spryker\Zed\QuoteApproval\Persistence\Propel\Mapper\QuoteApprovalMapper;
use Spryker\Zed\QuoteApproval\Persistence\Propel\Mapper\QuoteApprovalMapperInterface;

class QuoteApprovalPersistenceFactory
{
    /**
     * @return \Orm\Zed\QuoteApproval\Persistence\SpyQuoteApprovalQuery
     */
    public function createQuoteApprovalQuery(): SpyQuoteApprovalQuery
    {
        return new SpyQuoteApprovalQuery();
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Persistence\Propel\Mapper\QuoteApprovalMapperInterface
     */
    public function createQuoteApprovalMapper(): QuoteApprovalMapperInterface
    {
        return new QuoteApprovalMapper();
    }
}
