<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

class ProductPageSearchToSearchBridge implements ProductPageSearchToSearchInterface
{
    /**
     * @var \Spryker\Zed\Search\Business\SearchFacadeInterface
     */
    protected $searchFacade;

    /**
     * @param \Spryker\Zed\Search\Business\SearchFacadeInterface $searchFacade
     */
    public function __construct($searchFacade)
    {
        $this->searchFacade = $searchFacade;
    }

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param string $mapperName
     *
     * @return array
     */
    public function transformPageMapToDocumentByMapperName(array $data, LocaleTransfer $localeTransfer, $mapperName)
    {
        return $this->searchFacade->transformPageMapToDocumentByMapperName($data, $localeTransfer, $mapperName);
    }
}
