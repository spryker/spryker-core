<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearchExtension\Dependency;

use Generated\Shared\Transfer\PageMapTransfer;

/**
 * deprecated: This interface will not be used in PluginInterfaces in the next Search module major
 */
interface PageMapBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $fieldName
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function add(PageMapTransfer $pageMapTransfer, $fieldName, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function addSearchResultData(PageMapTransfer $pageMapTransfer, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string|array $value
     *
     * @return $this
     */
    public function addFullText(PageMapTransfer $pageMapTransfer, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string|array $value
     *
     * @return $this
     */
    public function addFullTextBoosted(PageMapTransfer $pageMapTransfer, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string|array $value
     *
     * @return $this
     */
    public function addSuggestionTerms(PageMapTransfer $pageMapTransfer, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string|array $value
     *
     * @return $this
     */
    public function addCompletionTerms(PageMapTransfer $pageMapTransfer, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param string|array $value
     *
     * @return $this
     */
    public function addStringFacet(PageMapTransfer $pageMapTransfer, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param int|array $value
     *
     * @return $this
     */
    public function addIntegerFacet(PageMapTransfer $pageMapTransfer, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function addStringSort(PageMapTransfer $pageMapTransfer, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param string $name
     * @param int $value
     *
     * @return $this
     */
    public function addIntegerSort(PageMapTransfer $pageMapTransfer, $name, $value);

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $allParents
     * @param array $directParents
     *
     * @return $this
     */
    public function addCategory(PageMapTransfer $pageMapTransfer, array $allParents, array $directParents);
}
