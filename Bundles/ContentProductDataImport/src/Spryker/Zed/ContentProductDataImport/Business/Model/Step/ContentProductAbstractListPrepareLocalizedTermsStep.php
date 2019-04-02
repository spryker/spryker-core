<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\Step;

use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Orm\Zed\Content\Persistence\Map\SpyContentLocalizedTableMap;
use Spryker\Zed\ContentProductDataImport\Business\Model\DataSet\ContentProductAbstractListDataSetInterface;
use Spryker\Zed\ContentProductDataImport\Dependency\Facade\ContentProductDataImportToContentProductFacadeInterface;
use Spryker\Zed\ContentProductDataImport\Dependency\Service\ContentProductDataImportToUtilEncodingServiceInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentProductAbstractListPrepareLocalizedTermsStep implements DataImportStepInterface
{
    protected const ERROR_MESSAGE_SUFFIX = 'Please check row with key: "{key}", column: {column}';
    protected const ERROR_MESSAGE_PARAMETER_COLUMN = '{column}';
    protected const ERROR_MESSAGE_PARAMETER_KEY = '{key}';

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
    public function execute(DataSetInterface $dataSet): void
    {
        $contentLocalizedItems = [];

        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $localeKeyIds = ContentProductAbstractListDataSetInterface::COLUMN_IDS . '.' . $localeName;

            if (!isset($dataSet[$localeKeyIds]) || !$dataSet[$localeKeyIds]) {
                continue;
            }

            $localizedItem[SpyContentLocalizedTableMap::COL_FK_LOCALE] = $idLocale ?? $idLocale;

            $contentProductAbstractListTermTransfer = (new ContentProductAbstractListTermTransfer())
                ->setIdProductAbstracts($dataSet[$localeKeyIds]);

            $contentValidationResponseTransfer = $this->contentProductFacade->validateContentProductAbstractListTerm($contentProductAbstractListTermTransfer);

            if (!$contentValidationResponseTransfer->getIsSuccess()) {
                $messageTransfer = $contentValidationResponseTransfer->getParameterMessages()
                    ->offsetGet(0)
                    ->getMessages()
                    ->offsetGet(0);

                $parameters = array_merge($messageTransfer->getParameters(), [
                    static::ERROR_MESSAGE_PARAMETER_COLUMN => '[' . ContentProductAbstractListDataSetInterface::COLUMN_SKUS . '.' . $localeName . ']',
                    static::ERROR_MESSAGE_PARAMETER_KEY => $dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PRODUCT_ABSTRACT_LIST_KEY],
                ]);
                $message = $messageTransfer->getValue() . ' ' . static::ERROR_MESSAGE_SUFFIX;
                $this->createInvalidDataImportException($message, $parameters);
            }

            $localizedItem[SpyContentLocalizedTableMap::COL_PARAMETERS] = $this->utilEncodingService->encodeJson(
                $contentProductAbstractListTermTransfer->toArray()
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
    protected function createInvalidDataImportException(string $message, array $parameters = []): void
    {
        $errorMessage = strtr($message, $parameters);

        throw new InvalidDataException($errorMessage);
    }
}
