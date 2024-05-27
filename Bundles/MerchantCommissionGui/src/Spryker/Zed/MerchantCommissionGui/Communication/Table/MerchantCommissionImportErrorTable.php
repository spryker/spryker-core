<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui\Communication\Table;

use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToGlossaryFacadeInterface;

class MerchantCommissionImportErrorTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const COL_IDENTIFIER = 'identifier';

    /**
     * @var string
     */
    protected const COL_ERROR = 'error';

    /**
     * @var string
     */
    protected const HEADER_ROW_NUMBER = 'Row n°';

    /**
     * @var string
     */
    protected const HEADER_ERROR = 'Error';

    /**
     * @var \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    protected MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionResponseTransfer;

    /**
     * @var \Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToGlossaryFacadeInterface
     */
    protected MerchantCommissionGuiToGlossaryFacadeInterface $glossaryFacade;

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionResponseTransfer
     * @param \Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(
        MerchantCommissionCollectionResponseTransfer $merchantCommissionCollectionResponseTransfer,
        MerchantCommissionGuiToGlossaryFacadeInterface $glossaryFacade
    ) {
        $this->merchantCommissionCollectionResponseTransfer = $merchantCommissionCollectionResponseTransfer;
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->disableSearch();

        $config->setHeader([
            static::COL_IDENTIFIER => static::HEADER_ROW_NUMBER,
            static::COL_ERROR => static::HEADER_ERROR,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return list<array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $data = [];
        foreach ($this->merchantCommissionCollectionResponseTransfer->getErrors() as $errorTransfer) {
            $data[] = [
                static::COL_IDENTIFIER => $errorTransfer->getEntityIdentifierOrFail(),
                static::COL_ERROR => $this->glossaryFacade->translate(
                    $errorTransfer->getMessageOrFail(),
                    $errorTransfer->getParameters(),
                ),
            ];
        }

        $errorIdentifiers = array_column($data, static::COL_IDENTIFIER);
        array_multisort($errorIdentifiers, SORT_ASC, $data);

        return $data;
    }
}
