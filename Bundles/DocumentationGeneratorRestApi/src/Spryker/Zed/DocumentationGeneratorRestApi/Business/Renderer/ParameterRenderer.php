<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer;

use Generated\Shared\Transfer\ParameterComponentTransfer;
use Generated\Shared\Transfer\ParameterTransfer;
use Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\ParameterSpecificationComponentInterface;

class ParameterRenderer implements ParameterRendererInterface
{
    /**
     * @var \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\ParameterSpecificationComponentInterface
     */
    protected $parameterSpecificationComponent;

    /**
     * @param \Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component\ParameterSpecificationComponentInterface $parameterSpecificationComponent
     */
    public function __construct(ParameterSpecificationComponentInterface $parameterSpecificationComponent)
    {
        $this->parameterSpecificationComponent = $parameterSpecificationComponent;
    }

    /**
     * @param \Generated\Shared\Transfer\ParameterTransfer $parameterTransfer
     *
     * @return array
     */
    public function render(ParameterTransfer $parameterTransfer): array
    {
        $parameterComponentTransfer = new ParameterComponentTransfer();
        $parameterComponentTransfer->setRefName($parameterTransfer->getRefName());
        $parameterComponentTransfer->setName($parameterTransfer->getName());
        $parameterComponentTransfer->setIn($parameterTransfer->getIn());
        $parameterComponentTransfer->setDescription($parameterTransfer->getDescription());
        $parameterComponentTransfer->setRequired($parameterTransfer->getRequired());
        $parameterComponentTransfer->setSchema($parameterTransfer->getSchema());

        $this->parameterSpecificationComponent->setParameterComponentTransfer($parameterComponentTransfer);

        return $this->parameterSpecificationComponent->getSpecificationComponentData();
    }
}
