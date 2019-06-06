<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Table;

use Generated\Shared\Transfer\PriceProductScheduleImportMetaDataTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToTranslatorFacadeInterface;

class ImportErrorListTable extends AbstractTable
{
    protected const HEADER_ROW_NUMBER = 'Row n°';
    protected const HEADER_ERROR = 'Error';

    /**
     * @var \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer
     */
    protected $priceProductScheduleListImportResponseTransfer;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToTranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer $priceProductScheduleListImportResponseTransfer
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToTranslatorFacadeInterface $translatorFacade
     */
    public function __construct(
        PriceProductScheduleListImportResponseTransfer $priceProductScheduleListImportResponseTransfer,
        PriceProductScheduleGuiToTranslatorFacadeInterface $translatorFacade
    ) {
        $this->priceProductScheduleListImportResponseTransfer = $priceProductScheduleListImportResponseTransfer;
        $this->translatorFacade = $translatorFacade;
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
            PriceProductScheduleImportMetaDataTransfer::IDENTIFIER => $this->trans(static::HEADER_ROW_NUMBER),
            PriceProductScheduleListImportErrorTransfer::MESSAGE => $this->trans(static::HEADER_ERROR),
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $data = [];

        foreach ($this->priceProductScheduleListImportResponseTransfer->getErrors() as $priceProductScheduleListImportErrorTransfer) {
            $data[] = [
                PriceProductScheduleImportMetaDataTransfer::IDENTIFIER => $priceProductScheduleListImportErrorTransfer->getPriceProductScheduleImport()->getMetaData()->getIdentifier(),
                PriceProductScheduleListImportErrorTransfer::MESSAGE => $this->trans(
                    $priceProductScheduleListImportErrorTransfer->getMessage(),
                    $priceProductScheduleListImportErrorTransfer->getParameters()
                ),
            ];
        }

        return $data;
    }

    /**
     * @param string $id
     * @param array $params
     *
     * @return string
     */
    protected function trans(string $id, array $params = []): string
    {
        return $this->translatorFacade->trans($id, $params);
    }
}
