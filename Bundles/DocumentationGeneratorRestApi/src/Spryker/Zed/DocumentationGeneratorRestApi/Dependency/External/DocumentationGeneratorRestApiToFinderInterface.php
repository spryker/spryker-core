<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Dependency\External;

use IteratorAggregate;

/**
 * @SuppressWarnings(PHPMD.ShortMethodName)
 *
 * @extends \IteratorAggregate<\SplFileInfo>
 */
interface DocumentationGeneratorRestApiToFinderInterface extends IteratorAggregate
{
    /**
     * @param array|string $dirs
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function in($dirs);

    /**
     * @return $this
     */
    public function files();

    /**
     * @param string $pattern
     *
     * @return $this
     */
    public function name(string $pattern);
}
