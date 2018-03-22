<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerApi\Business\Mapper;

interface EntityMapperInterface
{
    /**
     * @param array $data
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    public function toEntity(array $data);

    /**
     * @param array $data
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer[]
     */
    public function toEntityCollection(array $data);
}
