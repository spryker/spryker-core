<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Provider;

use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

interface PreProcessorProviderInterface
{
    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildFilterPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildPathPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildAddActionPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildFindActionPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildGetActionPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildUpdateActionPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildFieldsByQueryPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildPaginationByHeaderFilterPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildCriteriaByQueryFilterPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildPaginationByQueryFilterPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildSortByQueryFilterPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildFormatTypeByHeaderPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildFormatTypeByPathPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildResourceActionPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildResourceParamametersPreProcessor(): PreProcessorInterface;

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface
     */
    public function buildResourcePreProcessor(): PreProcessorInterface;
}
