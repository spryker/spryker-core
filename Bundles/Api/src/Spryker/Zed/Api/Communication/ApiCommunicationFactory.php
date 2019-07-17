<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\ApiDependencyProvider;
use Spryker\Zed\Api\Business\Exception\FormatterNotFoundException;
use Spryker\Zed\Api\Communication\Formatter\JsonFormatter;
use Spryker\Zed\Api\Communication\Plugin\ApiControllerListenerPlugin;
use Spryker\Zed\Api\Communication\Transformer\Transformer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\Api\ApiConfig getConfig()
 * @method \Spryker\Zed\Api\Business\ApiFacadeInterface getFacade()
 * @method \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface getQueryContainer()
 */
class ApiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param string $formatType
     *
     * @throws \Spryker\Zed\Api\Business\Exception\FormatterNotFoundException
     *
     * @return \Spryker\Zed\Api\Communication\Formatter\FormatterInterface
     */
    public function createFormatter($formatType)
    {
        if (!$formatType) {
            $formatType = 'json';
        }
        switch ($formatType) {
            case 'json':
                return new JsonFormatter($this->getUtilEncoding());
        }

        throw new FormatterNotFoundException(sprintf('Formatter for type `%s` not found', $formatType));
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Spryker\Zed\Api\Communication\Transformer\TransformerInterface
     */
    public function createTransformer(ApiRequestTransfer $apiRequestTransfer)
    {
        return new Transformer(
            $this->createFormatter($apiRequestTransfer->getFormatType()),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected function getUtilEncoding()
    {
        return $this->getProvidedDependency(ApiDependencyProvider::SERVICE_ENCODING);
    }

    /**
     * @return \Spryker\Zed\Api\Communication\Plugin\ApiControllerListenerInterface
     */
    public function createControllerListener()
    {
        return new ApiControllerListenerPlugin();
    }
}
