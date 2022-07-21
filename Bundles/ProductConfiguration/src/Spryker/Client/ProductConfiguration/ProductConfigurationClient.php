<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductConfiguration\ProductConfigurationFactory getFactory()
 */
class ProductConfigurationClient extends AbstractClient implements ProductConfigurationClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function validateProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        return $this->getFactory()
            ->createProductConfiguratorCheckSumResponseValidatorComposite()
            ->validateProductConfiguratorCheckSumResponse($productConfiguratorResponseProcessorResponseTransfer, $configuratorResponseData);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer
     */
    public function expandProductConfiguratorRequestWithContextData(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRequestTransfer {
        return $this->getFactory()
            ->createProductConfiguratorRequestExpander()
            ->expandProductConfiguratorRequestWithContextData($productConfiguratorRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function sendProductConfiguratorAccessTokenRequest(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        return $this->getFactory()
            ->createProductConfiguratorRequestSender()
            ->sendProductConfiguratorAccessTokenRequest($productConfiguratorRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string, mixed> $configuratorResponseData
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer
     */
    public function mapProductConfiguratorCheckSumResponse(
        array $configuratorResponseData,
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
    ): ProductConfiguratorResponseTransfer {
        return $this->getFactory()
            ->createProductConfigurationResponseMapper()
            ->mapConfiguratorResponseDataToProductConfiguratorResponseTransfer(
                $configuratorResponseData,
                $productConfiguratorResponseTransfer,
            );
    }
}
