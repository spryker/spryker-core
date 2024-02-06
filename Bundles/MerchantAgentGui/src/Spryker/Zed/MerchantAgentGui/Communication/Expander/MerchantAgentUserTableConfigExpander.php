<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantAgentGui\Communication\Expander;

use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class MerchantAgentUserTableConfigExpander implements MerchantAgentUserTableConfigExpanderInterface
{
    /**
     * @uses \Orm\Zed\User\Persistence\Map\SpyUserTableMap::COL_STATUS
     *
     * @var string
     */
    protected const COL_STATUS = 'spy_user.status';

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function expandConfig(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setMerchantAgentHeader($config);
        $config = $this->setRawMerchantAgentColumn($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setMerchantAgentHeader(TableConfiguration $config): TableConfiguration
    {
        $header = $this->insertAfterHeader($config->getHeader(), static::COL_STATUS, [
            UserTransfer::IS_MERCHANT_AGENT => 'Agent Merchant',
        ]);

        $config->setHeader($header);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setRawMerchantAgentColumn(TableConfiguration $config): TableConfiguration
    {
        $config->addRawColumn(UserTransfer::IS_MERCHANT_AGENT);

        return $config;
    }

    /**
     * @param array<string, string> $header
     * @param string $key
     * @param array<string, string> $newColumns
     *
     * @return array<string, string>
     */
    protected function insertAfterHeader(array $header, string $key, array $newColumns): array
    {
        $newHeader = [];
        $found = false;

        foreach ($header as $headerKey => $headerValue) {
            $newHeader[$headerKey] = $headerValue;

            if ($headerKey === $key) {
                $newHeader = array_merge($newHeader, $newColumns);
                $found = true;
            }
        }

        if (!$found) {
            $newHeader = array_merge($newHeader, $newColumns);
        }

        return $newHeader;
    }
}
