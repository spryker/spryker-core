<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Dependency\External;

use IteratorAggregate;

/**
 * @SuppressWarnings(PHPMD.ShortMethodName)
 */
interface RestApiDocumentationGeneratorToFinderInterface extends IteratorAggregate
{
    /**
     * @return $this
     */
    public function sortByName();

    /**
     * @param string|array $dirs
     *
     * @return $this
     */
    public function in($dirs);

    /**
     * @param string $pattern
     *
     * @return $this
     */
    public function name($pattern);

    /**
     * @return \SplFileInfo[]
     */
    public function getIterator();
}
