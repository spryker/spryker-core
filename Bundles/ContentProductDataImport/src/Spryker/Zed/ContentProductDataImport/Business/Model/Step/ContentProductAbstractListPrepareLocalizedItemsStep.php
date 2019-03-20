<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\Step;

use Generated\Shared\Transfer\ContentProductAbstractListTransfer;
use Orm\Zed\Content\Persistence\Map\SpyContentLocalizedTableMap;
use Spryker\Zed\ContentProductDataImport\Business\Model\DataSet\ContentProductAbstractListDataSetInterface;
use Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentProductFacadeInterface;
use Spryker\Zed\ContentProductDataImport\Dependency\Service\ContentProductDataImportToUtilEncodingServiceInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentProductAbstractListPrepareLocalizedItemsStep implements DataImportStepInterface
{
    protected const EXCEPTION_ERROR_MESSAGE_PARAMETER_COLUMN = '{column}';
    protected const EXCEPTION_ERROR_MESSAGE_PARAMETER_KEY = '{key}';

    /**
     * @var \Spryker\Zed\ContentProductDataImport\Dependency\Service\ContentProductDataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentProductFacadeInterface
     */
    protected $contentProductFacade;

    /**
     * @param \Spryker\Zed\ContentProductDataImport\Dependency\Service\ContentProductDataImportToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentProductFacadeInterface $contentProductFacade
     */
    public function __construct(
        ContentProductDataImportToUtilEncodingServiceInterface $utilEncodingService,
        ContentProductDataImportToContentProductFacadeInterface $contentProductFacade
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->contentProductFacade = $contentProductFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $contentLocalizedItems = [];

        foreach ($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_LOCALES] as $localeId => $localeName) {
            $idsLocaleKey = ContentProductAbstractListDataSetInterface::COLUMN_IDS . '.' . $localeName;

            if (!isset($dataSet[$idsLocaleKey]) || !$dataSet[$idsLocaleKey]) {
                continue;
            }

            $localizedItem[SpyContentLocalizedTableMap::COL_FK_LOCALE] = $localeId > 0 ? $localeId : null;
            $localizedItem[SpyContentLocalizedTableMap::COL_FK_CONTENT] = $dataSet[ContentProductAbstractListDataSetInterface::COLUMN_ID_CONTENT];

            $contentProductAbstractListTransfer = (new ContentProductAbstractListTransfer())
                ->setIdProductAbstracts($dataSet[$idsLocaleKey]);

            $contentValidationResponseTransfer = $this->contentProductFacade->validateContentProductAbstractList($contentProductAbstractListTransfer);

            if (!$contentValidationResponseTransfer->getIsSuccess()) {
                $messageTransfer = $contentValidationResponseTransfer->getParameterMessages()
                    ->offsetGet(0)
                    ->getMessages()
                    ->offsetGet(0);
                $kusLocaleColumn = ContentProductAbstractListDataSetInterface::COLUMN_SKUS . '.' . $localeName;
                $rowKey = $dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PROCUCT_ABSTRACT_LIST_KEY];
                $parameters = array_merge($messageTransfer->getParameters(), [
                    static::EXCEPTION_ERROR_MESSAGE_PARAMETER_COLUMN => $kusLocaleColumn,
                    static::EXCEPTION_ERROR_MESSAGE_PARAMETER_KEY => $rowKey,
                ]);
                $this->creteInvalidDataImportException($messageTransfer->getValue(), $parameters);
            }

            $localizedItem[SpyContentLocalizedTableMap::COL_PARAMETERS] = $this->utilEncodingService->encodeJson(
                $contentProductAbstractListTransfer->toArray()
            );

            $contentLocalizedItems[] = $localizedItem;
        }

        $dataSet[ContentProductAbstractListDataSetInterface::CONTENT_LOCALIZED_ITEMS] = $contentLocalizedItems;
    }

    /**
     * @param string $message
     * @param array $parameters
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function creteInvalidDataImportException(string $message, array $parameters)
    {
        $errorMessage = strtr($message, $parameters);

        throw new InvalidDataException($errorMessage);
    }
}
