<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\QuoteRequestTransfer;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest;
use Spryker\Zed\AgentQuoteRequest\Dependency\Service\AgentQuoteRequestToUtilEncodingServiceInterface;

class QuoteRequestMapper
{
    /**
     * @var \Spryker\Zed\AgentQuoteRequest\Dependency\Service\AgentQuoteRequestToUtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @param \Spryker\Zed\AgentQuoteRequest\Dependency\Service\AgentQuoteRequestToUtilEncodingServiceInterface $encodingService
     */
    public function __construct(AgentQuoteRequestToUtilEncodingServiceInterface $encodingService)
    {
        $this->encodingService = $encodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest $quoteRequestEntity
     *
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest
     */
    public function mapQuoteRequestTransferToQuoteRequestEntity(
        QuoteRequestTransfer $quoteRequestTransfer,
        SpyQuoteRequest $quoteRequestEntity
    ): SpyQuoteRequest {
        $data = $quoteRequestTransfer->modifiedToArray();
        unset($data['metadata']);

        $quoteRequestEntity->fromArray($data);

        $quoteRequestEntity->setFkCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser());
        $quoteRequestEntity->setMetadata($this->encodeMetadata($quoteRequestTransfer));

        return $quoteRequestEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return string
     */
    protected function encodeMetadata(QuoteRequestTransfer $quoteRequestTransfer): string
    {
        return $this->encodingService->encodeJson($quoteRequestTransfer->getMetadata(), JSON_OBJECT_AS_ARRAY);
    }
}
