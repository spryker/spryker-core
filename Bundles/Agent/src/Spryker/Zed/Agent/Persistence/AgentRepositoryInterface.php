<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Persistence;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;

interface AgentRepositoryInterface
{
    /**
     * @param string $username
     *
     * @return \Generated\Shared\Transfer\UserTransfer|null
     */
    public function findAgentByUsername(string $username): ?UserTransfer;

    /**
     * @param string $query
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return \Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer
     */
    public function findCustomersByQuery(string $query, ?int $limit, ?int $offset): CustomerAutocompleteResponseTransfer;
}
