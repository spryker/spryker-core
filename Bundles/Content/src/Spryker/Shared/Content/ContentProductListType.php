<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Content;

class ContentProductListType implements ContentTypeInterface
{
    /**
     * @var string
     */
    public const CONTENT_TYPE = "CONTENT_PRODUCT_LIST_TYPE";

    /**
     * @var \Spryker\Shared\Content\ContentItemCategory $category
     */
    protected $category;

    /**
     * @param \Spryker\Shared\Content\ContentItemCategory $category
     */
    public function __construct(ContentItemCategory $category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getCategoryCandidateKey(): string
    {
        return $this->category->getCandidateKey();
    }

    /**
     * @return string
     */
    public function getCandidateKey(): string
    {
        return static::CONTENT_TYPE;
    }
}
