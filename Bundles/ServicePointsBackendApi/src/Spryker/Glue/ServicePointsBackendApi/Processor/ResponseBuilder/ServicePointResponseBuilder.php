<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ApiServicePointsAttributesTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface;
use Spryker\Glue\ServicePointsBackendApi\Processor\Translator\ServicePointTranslatorInterface;
use Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ServicePointResponseBuilder implements ServicePointResponseBuilderInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_LOCALE_NAME = 'en_US';

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig
     */
    protected ServicePointsBackendApiConfig $servicePointsBackendApiConfig;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Translator\ServicePointTranslatorInterface
     */
    protected ServicePointTranslatorInterface $servicePointTranslator;

    /**
     * @var \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface
     */
    protected ServicePointMapperInterface $servicePointMapper;

    /**
     * @param \Spryker\Glue\ServicePointsBackendApi\ServicePointsBackendApiConfig $servicePointsBackendApiConfig
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Translator\ServicePointTranslatorInterface $servicePointTranslator
     * @param \Spryker\Glue\ServicePointsBackendApi\Processor\Mapper\ServicePointMapperInterface $servicePointMapper
     */
    public function __construct(
        ServicePointsBackendApiConfig $servicePointsBackendApiConfig,
        ServicePointTranslatorInterface $servicePointTranslator,
        ServicePointMapperInterface $servicePointMapper
    ) {
        $this->servicePointsBackendApiConfig = $servicePointsBackendApiConfig;
        $this->servicePointTranslator = $servicePointTranslator;
        $this->servicePointMapper = $servicePointMapper;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createServicePointResponse(
        ArrayObject $servicePointTransfers
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($servicePointTransfers as $servicePointTransfer) {
            $glueResponseTransfer->addResource(
                $this->createServicePointResourceTransfer($servicePointTransfer),
            );
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     * @param string|null $localeName
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createServicePointErrorResponse(
        ArrayObject $errorTransfers,
        ?string $localeName
    ): GlueResponseTransfer {
        $validationGlossaryKeyToRestErrorMapping = $this->servicePointsBackendApiConfig->getGlossaryKeyToErrorDataMapping();

        $translations = $this->servicePointTranslator->translateErrorTransferMessages(
            $errorTransfers,
            $localeName ?? static::DEFAULT_LOCALE_NAME,
        );

        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($errorTransfers as $errorTransfer) {
            $glueErrorTransfer = $this->createGlueErrorTransfer(
                $errorTransfer->getMessageOrFail(),
                $translations,
                $validationGlossaryKeyToRestErrorMapping,
            );

            $glueResponseTransfer->addError($glueErrorTransfer);
        }

        return $this->setGlueResponseHttpStatus($glueResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResourceTransfer
     */
    protected function createServicePointResourceTransfer(
        ServicePointTransfer $servicePointTransfer
    ): GlueResourceTransfer {
        $apiServicePointsAttributesTransfer = $this->servicePointMapper
            ->mapServicePointTransferToApiServicePointsAttributesTransfer(
                $servicePointTransfer,
                new ApiServicePointsAttributesTransfer(),
            );

        return (new GlueResourceTransfer())
            ->setId($servicePointTransfer->getUuidOrFail())
            ->setType(ServicePointsBackendApiConfig::RESOURCE_SERVICE_POINTS)
            ->setAttributes($apiServicePointsAttributesTransfer);
    }

    /**
     * @param string $glossaryKey
     * @param array<string, string> $translations
     * @param array<string, array<string, mixed>> $validationGlossaryKeyToRestErrorMapping
     *
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createGlueErrorTransfer(
        string $glossaryKey,
        array $translations,
        array $validationGlossaryKeyToRestErrorMapping
    ): GlueErrorTransfer {
        if (!isset($validationGlossaryKeyToRestErrorMapping[$glossaryKey])) {
            return $this->createUnknownGlueErrorTransfer($translations[$glossaryKey]);
        }

        return (new GlueErrorTransfer())
            ->fromArray($validationGlossaryKeyToRestErrorMapping[$glossaryKey])
            ->setMessage($translations[$glossaryKey]);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function setGlueResponseHttpStatus(GlueResponseTransfer $glueResponseTransfer): GlueResponseTransfer
    {
        $glueErrorTransfers = $glueResponseTransfer->getErrors();

        if ($glueErrorTransfers->count() !== 1) {
            return $glueResponseTransfer->setHttpStatus(
                Response::HTTP_MULTI_STATUS,
            );
        }

        $glueErrorTransfer = $glueErrorTransfers->getIterator()->current();

        return $glueResponseTransfer->setHttpStatus(
            $glueErrorTransfer->getStatus(),
        );
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createUnknownGlueErrorTransfer(string $message): GlueErrorTransfer
    {
        return (new GlueErrorTransfer())
            ->setMessage($message)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setCode(ServicePointsBackendApiConfig::RESPONSE_CODE_UNKNOWN_ERROR);
    }
}
