<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentGui\Communication\TableDataExpander;

use Orm\Zed\User\Persistence\Map\SpyUserTableMap;

class UserAgentTableDataExpander implements UserAgentTableDataExpanderInterface
{
    /**
     * @param array $item
     *
     * @return array
     */
    public function expandData(array $item): array
    {
        return [
            SpyUserTableMap::COL_IS_AGENT => $this->createIsAgentLabel($item[SpyUserTableMap::COL_IS_AGENT]),
        ];
    }

    /**
     * @param bool|null $isAgent
     *
     * @return string
     */
    protected function createIsAgentLabel(?bool $isAgent): string
    {
        return $isAgent ? '<span class="label label-success" title="Agent">Agent</span>' : '';
    }
}
