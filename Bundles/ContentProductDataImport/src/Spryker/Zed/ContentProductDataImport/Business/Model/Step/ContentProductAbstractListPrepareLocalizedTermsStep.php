<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\Step;

use Generated\Shared\Transfer\ContentProductAbstractListTermTransfer;
use Generated\Shared\Transfer\ContentValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
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
        $localizedProductAbstractListTermParameters = [];

        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $localizedColumnIds = ContentProductAbstractListDataSetInterface::COLUMN_IDS . '.' . $localeName;

            if (!isset($dataSet[$localizedColumnIds]) || !$dataSet[$localizedColumnIds]) {
                continue;
            }

            $localizedProductAbstractListTermParameter[SpyContentLocalizedTableMap::COL_FK_LOCALE] = $idLocale ?? $idLocale;
            $contentProductAbstractListTermTransfer = (new ContentProductAbstractListTermTransfer())->setIdProductAbstracts($dataSet[$localizedColumnIds]);
            $contentValidationResponseTransfer = $this->contentProductFacade->validateContentProductAbstractListTerm($contentProductAbstractListTermTransfer);

            if (!$contentValidationResponseTransfer->getIsSuccess()) {
                $messageTransfer = $this->getMessageTransfer($contentValidationResponseTransfer);
                $this->createInvalidDataImportException(
                    $this->getMessage($messageTransfer),
                    $this->getParameters($messageTransfer, $dataSet, $localeName)
                );
            }

            $localizedProductAbstractListTermParameter[SpyContentLocalizedTableMap::COL_PARAMETERS] = $this->getEncodedParameters($contentProductAbstractListTermTransfer);
            $localizedProductAbstractListTermParameters[] = $localizedProductAbstractListTermParameter;
        }

        $dataSet[ContentProductAbstractListDataSetInterface::CONTENT_LOCALIZED_PRODUCT_ABSTRACT_LIST_TERMS] = $localizedProductAbstractListTermParameters;
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

    /**
     * @param \Generated\Shared\Transfer\ContentValidationResponseTransfer $contentValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function getMessageTransfer(ContentValidationResponseTransfer $contentValidationResponseTransfer): MessageTransfer
    {
        return $contentValidationResponseTransfer->getParameterMessages()
            ->offsetGet(0)
            ->getMessages()
            ->offsetGet(0);
    }

    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer
     *
     * @return string|null
     */
    protected function getEncodedParameters(ContentProductAbstractListTermTransfer $contentProductAbstractListTermTransfer): ?string
    {
        return $this->utilEncodingService->encodeJson($contentProductAbstractListTermTransfer->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return string
     */
    protected function getMessage(MessageTransfer $messageTransfer): string
    {
        return sprintf('%s %s', $messageTransfer->getValue(), static::ERROR_MESSAGE_SUFFIX);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet $dataSet
     * @param string $localeName
     *
     * @return array
     */
    protected function getParameters(MessageTransfer $messageTransfer, DataSetInterface $dataSet, string $localeName): array
    {
        return array_merge($messageTransfer->getParameters(), [
            static::ERROR_MESSAGE_PARAMETER_COLUMN => sprintf('[%s.%s]', ContentProductAbstractListDataSetInterface::COLUMN_SKUS, $localeName),
            static::ERROR_MESSAGE_PARAMETER_KEY => $dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PRODUCT_ABSTRACT_LIST_KEY],
        ]);
    }
}
