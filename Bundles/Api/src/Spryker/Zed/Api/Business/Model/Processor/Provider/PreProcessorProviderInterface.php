<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Provider;

interface PreProcessorProviderInterface
{

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\FilterPreProcessor
     */
    public function buildFilterPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PaginationPreProcessor
     */
    public function buildPaginationPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\PathPreProcessor
     */
    public function buildPathPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Action\AddActionPreProcessor
     */
    public function buildAddActionPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Action\FindActionPreProcessor
     */
    public function buildFindActionPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Action\GetActionPreProcessor
     */
    public function buildGetActionPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Action\UpdateActionPreProcessor
     */
    public function buildUpdateActionPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Fields\FieldsByQueryPreProcessor
     */
    public function buildFieldsByQueryPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Header\PaginationByHeaderFilterPreProcessor
     */
    public function buildPaginationByHeaderFilterPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\CriteriaByQueryFilterPreProcessor
     */
    public function buildCriteriaByQueryFilterPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\PaginationByQueryFilterPreProcessor
     */
    public function buildPaginationByQueryFilterPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query\SortByQueryFilterPreProcessor
     */
    public function buildSortByQueryFilterPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Format\FormatTypeByHeaderPreProcessor
     */
    public function buildFormatTypeByHeaderPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Format\FormatTypeByPathPreProcessor
     */
    public function buildFormatTypeByPathPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceActionPreProcessor
     */
    public function buildResourceActionPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourceParametersPreProcessor
     */
    public function buildResourceParamametersPreProcessor();

    /**
     * @return \Spryker\Zed\Api\Business\Model\Processor\Pre\Resource\ResourcePreProcessor
     */
    public function buildResourcePreProcessor();

}
