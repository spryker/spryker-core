<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\DataImportMerchant\Business\Provider;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\DataImportMerchant\DataImportMerchantConfig;

class PossibleCsvHeaderProvider implements PossibleCsvHeaderProviderInterface
{
    /**
     * @param \Spryker\Zed\DataImportMerchant\DataImportMerchantConfig $dataImportMerchantConfig
     * @param list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\PossibleCsvHeaderExpanderPluginInterface> $possibleCsvHeaderExpanderPlugins
     */
    public function __construct(
        protected DataImportMerchantConfig $dataImportMerchantConfig,
        protected array $possibleCsvHeaderExpanderPlugins
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array<string, list<string>>
     */
    public function getPossibleCsvHeadersIndexedByImporterType(MerchantTransfer $merchantTransfer): array
    {
        $merchantTransfer->requireMerchantReference();

        $supportedImporterTypes = array_unique($this->dataImportMerchantConfig->getSupportedImporterTypes());
        $possibleCsvHeaders = array_fill_keys($supportedImporterTypes, []);

        return $this->executePossibleCsvHeaderExpanderPlugins($possibleCsvHeaders, $merchantTransfer);
    }

    /**
     * @param array<string, list<string>> $possibleCsvHeaders
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return array<string, list<string>>
     */
    protected function executePossibleCsvHeaderExpanderPlugins(array $possibleCsvHeaders, MerchantTransfer $merchantTransfer): array
    {
        foreach ($this->possibleCsvHeaderExpanderPlugins as $possibleCsvHeaderExpanderPlugin) {
            $possibleCsvHeaders = $possibleCsvHeaderExpanderPlugin->expand($possibleCsvHeaders, $merchantTransfer);
        }

        return $possibleCsvHeaders;
    }
}
