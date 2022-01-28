<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Post\Action;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Model\Processor\Post\PostProcessorInterface;

/**
 * Successful creation returns a 201 response code as well as a self link with the new primary key.
 */
class AddActionPostProcessor implements PostProcessorInterface
{
    /**
     * @var \Spryker\Zed\Api\ApiConfig
     */
    protected $apiConfig;

    /**
     * @param \Spryker\Zed\Api\ApiConfig $apiConfig
     */
    public function __construct(ApiConfig $apiConfig)
    {
        $this->apiConfig = $apiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $apiResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ApiResponseTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer, ApiResponseTransfer $apiResponseTransfer): ApiResponseTransfer
    {
        $action = $apiRequestTransfer->getResourceAction();
        if ($action !== ApiConfig::ACTION_CREATE) {
            return $apiResponseTransfer;
        }
        if ($apiResponseTransfer->getCode() !== null) {
            return $apiResponseTransfer;
        }

        $apiResponseTransfer->setCode(ApiConfig::HTTP_CODE_CREATED);

        $apiMetaTransfer = $apiResponseTransfer->getMeta();
        if ($apiMetaTransfer === null) {
            return $apiResponseTransfer;
        }

        $apiMetaTransfer->setSelf($this->createSelfLink(
            $this->apiConfig->getBaseUri(),
            $apiRequestTransfer->getResourceOrFail(),
            $apiMetaTransfer->getResourceIdOrFail(),
        ));

        return $apiResponseTransfer->setMeta($apiMetaTransfer);
    }

    /**
     * @param string $baseUri
     * @param string $resource
     * @param string $resourceId
     *
     * @return string
     */
    protected function createSelfLink(string $baseUri, string $resource, string $resourceId): string
    {
        return sprintf('%s%s/%s', $baseUri, $resource, $resourceId);
    }
}
