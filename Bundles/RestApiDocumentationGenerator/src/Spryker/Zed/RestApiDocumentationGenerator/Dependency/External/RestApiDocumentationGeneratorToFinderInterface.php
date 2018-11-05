<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Dependency\External;

use IteratorAggregate;

/**
 * @SuppressWarnings(PHPMD.ShortVariable)
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
interface RestApiDocumentationGeneratorToFinderInterface extends IteratorAggregate
{
    /**
     * @param string|array $dirs
     *
     * @throws \InvalidArgumentException
     *
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface
     */
    public function in($dirs): self;

    /**
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface
     */
    public function files(): self;

    /**
     * @param string $pattern
     *
     * @return \Spryker\Zed\RestApiDocumentationGenerator\Dependency\External\RestApiDocumentationGeneratorToFinderInterface
     */
    public function name(string $pattern): self;
}
