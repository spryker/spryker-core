<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAgentGui\Communication\Expander;

use Generated\Shared\Transfer\UserTransfer;

class MerchantAgentUserTableDataExpander implements MerchantAgentUserTableDataExpanderInterface
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_IS_MERCHANT_AGENT
     *
     * @var string
     */
    protected const COL_IS_MERCHANT_AGENT = 'spy_user.is_merchant_agent';

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    public function expandData(array $item): array
    {
        return [
            UserTransfer::IS_MERCHANT_AGENT => $this->createIsMerchantAgentLabel($item[static::COL_IS_MERCHANT_AGENT]),
        ];
    }

    /**
     * @param bool|null $isMerchantAgent
     *
     * @return string
     */
    protected function createIsMerchantAgentLabel(?bool $isMerchantAgent): string
    {
        return $isMerchantAgent ? '<span class="label label-success" title="Agent">Agent</span>' : '';
    }
}
