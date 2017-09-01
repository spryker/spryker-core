<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Communication\Table\Assignment;

interface AssignmentCustomerQueryBuilderInterface
{

    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function buildNotAssignedQuery($idCustomerGroup = null);

    /**
     * @param int|null $idCustomerGroup
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function buildAssignedQuery($idCustomerGroup = null);

}
