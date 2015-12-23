<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductSearch\Dependency\Facade;

interface ProductSearchToCollectorInterface
{

    /**
     * @return string
     */
    public function getSearchIndexName();

    /**
     * @return string
     */
    public function getSearchDocumentType();

}
