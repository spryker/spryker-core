<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Persistence\FieldMapper;

use Orm\Zed\Customer\Persistence\SpyCustomerQuery;

interface FieldMapperInterface
{

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomerQuery $query
     * @param array $allowedFields
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function mapFields(SpyCustomerQuery $query, array $allowedFields);

}
