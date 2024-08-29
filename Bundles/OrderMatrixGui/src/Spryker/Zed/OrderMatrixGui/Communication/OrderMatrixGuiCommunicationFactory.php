<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrixGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\OrderMatrixGui\Communication\DataExtractor\OrderMatrixDataExtractor;
use Spryker\Zed\OrderMatrixGui\Communication\DataExtractor\OrderMatrixDataExtractorInterface;
use Spryker\Zed\OrderMatrixGui\Communication\Formatter\OrderMatrixFormatter;
use Spryker\Zed\OrderMatrixGui\Communication\Formatter\OrderMatrixFormatterInterface;
use Spryker\Zed\OrderMatrixGui\Dependency\Facade\OrderMatrixGuiToOrderMatrixFacadeInterface;
use Spryker\Zed\OrderMatrixGui\Dependency\Service\OrderMatrixGuiToUtilSanitizeServiceInterface;
use Spryker\Zed\OrderMatrixGui\OrderMatrixGuiDependencyProvider;

/**
 * @method \Spryker\Zed\OrderMatrixGui\OrderMatrixGuiConfig getConfig()
 */
class OrderMatrixGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\OrderMatrixGui\Communication\Formatter\OrderMatrixFormatterInterface
     */
    public function createOrderMatrixFormatter(): OrderMatrixFormatterInterface
    {
        return new OrderMatrixFormatter(
            $this->getUtilSanitizeService(),
            $this->createOrderMatrixDataExtractor(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\OrderMatrixGui\Communication\DataExtractor\OrderMatrixDataExtractorInterface
     */
    public function createOrderMatrixDataExtractor(): OrderMatrixDataExtractorInterface
    {
        return new OrderMatrixDataExtractor();
    }

    /**
     * @return \Spryker\Zed\OrderMatrixGui\Dependency\Facade\OrderMatrixGuiToOrderMatrixFacadeInterface
     */
    public function getOrderMatrixFacade(): OrderMatrixGuiToOrderMatrixFacadeInterface
    {
        return $this->getProvidedDependency(OrderMatrixGuiDependencyProvider::FACADE_ORDER_MATRIX);
    }

    /**
     * @return \Spryker\Zed\OrderMatrixGui\Dependency\Service\OrderMatrixGuiToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService(): OrderMatrixGuiToUtilSanitizeServiceInterface
    {
        return $this->getProvidedDependency(OrderMatrixGuiDependencyProvider::SERVICE_UTIL_SANITIZE);
    }
}
