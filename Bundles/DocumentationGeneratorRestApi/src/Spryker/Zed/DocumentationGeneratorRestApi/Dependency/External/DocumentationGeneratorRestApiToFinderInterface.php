<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External;

use IteratorAggregate;

/**
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
interface DocumentationGeneratorRestApiToFinderInterface extends IteratorAggregate
{
    /**
     * @param string|array $dirs
     *
     * @throws \InvalidArgumentException
     *
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface
     */
    public function in($dirs): self;

    /**
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface
     */
    public function files(): self;

    /**
     * @param string $pattern
     *
     * @return \Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External\DocumentationGeneratorRestApiToFinderInterface
     */
    public function name(string $pattern): self;
}
