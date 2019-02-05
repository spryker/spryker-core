<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestCartsAttributesTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface CartsResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapCartsResource(QuoteTransfer $quoteTransfer, RestRequestInterface $restRequest): RestResourceInterface;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestCartsAttributesTransfer
     */
    public function mapQuoteTransferToRestCartsAttributesTransfer(QuoteTransfer $quoteTransfer): RestCartsAttributesTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestTransfer
     */
    public function mapRestQuoteRequestTransferFromRequest(
        QuoteResponseTransfer $quoteResponseTransfer,
        RestRequestInterface $restRequest
    ): RestQuoteRequestTransfer;

    /**
     * @param string|null $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestTransfer
     */
    public function mapRestQuoteRequestTransferByUuid(
        ?string $uuidCart,
        RestRequestInterface $restRequest
    ): RestQuoteRequestTransfer;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\RestQuoteRequestTransfer
     */
    public function createRestQuoteRequestTransfer(
        RestRequestInterface $restRequest,
        ?QuoteTransfer $quoteTransfer
    ): RestQuoteRequestTransfer;

    /**
     * @param bool $isSuccessful
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createRestQuoteResponseTransfer(
        bool $isSuccessful,
        ?QuoteTransfer $quoteTransfer
    ): QuoteResponseTransfer;

    /**
     * @param \Generated\Shared\Transfer\RestCartsAttributesTransfer $restCartsAttributesTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function mapRestCartsAttributesTransferToQuoteTransfer(
        RestCartsAttributesTransfer $restCartsAttributesTransfer,
        RestRequestInterface $restRequest
    ): QuoteTransfer;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer
     */
    public function mapRestRequestToRestQuoteCollectionRequestTransfer(
        RestRequestInterface $restRequest
    ): RestQuoteCollectionRequestTransfer;

    /**
     * @param string|null $uuidCart
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransfer(
        ?string $uuidCart,
        RestRequestInterface $restRequest
    ): QuoteTransfer;
}
