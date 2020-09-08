<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;

interface ConfigurableBundleCartMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer
     */
    public function mapRestConfiguredBundlesAttributesToCreateConfiguredBundleRequest(
        RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer,
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
    ): CreateConfiguredBundleRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuoteErrorTransfer $quoteErrorTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function mapQuoteErrorTransferToRestErrorMessageTransfer(
        QuoteErrorTransfer $quoteErrorTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer
    ): RestErrorMessageTransfer;
}
