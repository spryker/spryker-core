<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer;

use Generated\Shared\Transfer\ParameterComponentTransfer;
use Generated\Shared\Transfer\ParameterTransfer;
use Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\Component\ParameterSpecificationComponentInterface;

class ParameterRenderer implements ParameterRendererInterface
{
    /**
     * @var \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\Component\ParameterSpecificationComponentInterface
     */
    protected $parameterSpecificationComponent;

    /**
     * @param \Spryker\Glue\DocumentationGeneratorOpenApi\Formatter\Schema\Renderer\Component\ParameterSpecificationComponentInterface $parameterSpecificationComponent
     */
    public function __construct(ParameterSpecificationComponentInterface $parameterSpecificationComponent)
    {
        $this->parameterSpecificationComponent = $parameterSpecificationComponent;
    }

    /**
     * @param \Generated\Shared\Transfer\ParameterTransfer $parameterTransfer
     *
     * @return array<mixed>
     */
    public function render(ParameterTransfer $parameterTransfer): array
    {
        $parameterComponentTransfer = new ParameterComponentTransfer();
        $parameterComponentTransfer->fromArray($parameterTransfer->toArray(), true);

        return $this->parameterSpecificationComponent->getSpecificationComponentData($parameterComponentTransfer);
    }
}
