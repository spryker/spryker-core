<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchExtension\Dependency\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\SearchExtension\Business\PageMapBuilder\PageMapBuilderInterface;

/**
 * !!!THIS SHOULD GO TO SEARCHELASTICSEARCHEXTENSION MODULE. IT'S HERE ONLY FOR PROTOTYPE!!!
 */
interface PageMapInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\SearchExtension\Business\PageMapBuilder\PageMapBuilderInterface $pageMapBuilder
     * @param array $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function buildPageMap(PageMapBuilderInterface $pageMapBuilder, array $data, LocaleTransfer $locale);
}
