<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\StoresApi\Processor\Validator;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueRequestValidationTransfer;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreClientInterface;
use Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface;
use Spryker\Glue\StoresApi\StoresApiConfig;
use Symfony\Component\HttpFoundation\Response;

class StoreValidator implements StoreValidatorInterface
{
    /**
     * @var \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface
     */
    protected StoresApiToStoreStorageClientInterface $storeStorageClient;

    /**
     * @var \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreClientInterface
     */
    protected StoresApiToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreClientInterface $storeClient
     * @param \Spryker\Glue\StoresApi\Dependency\Client\StoresApiToStoreStorageClientInterface $storeStorageClient
     */
    public function __construct(
        StoresApiToStoreClientInterface $storeClient,
        StoresApiToStoreStorageClientInterface $storeStorageClient
    ) {
        $this->storeClient = $storeClient;
        $this->storeStorageClient = $storeStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueRequestValidationTransfer
     */
    public function validate(GlueRequestTransfer $glueRequestTransfer): GlueRequestValidationTransfer
    {
        $storeNames = $this->storeStorageClient->getStoreNames();
        if (in_array($this->storeClient->getCurrentStore()->getNameOrFail(), $storeNames, true)) {
            return (new GlueRequestValidationTransfer())->setIsValid(true);
        }

        return (new GlueRequestValidationTransfer())
            ->setIsValid(false)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->addError($this->createStoreNotFoundError());
    }

    /**
     * @return \Generated\Shared\Transfer\GlueErrorTransfer
     */
    protected function createStoreNotFoundError(): GlueErrorTransfer
    {
        return (new GlueErrorTransfer())
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setCode(StoresApiConfig::RESPONSE_CODE_STORE_NOT_FOUND)
            ->setMessage(StoresApiConfig::GLOSSARY_KEY_VALIDATION_STORE_NOT_FOUND);
    }
}
