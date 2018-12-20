<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Content;

class ContentAbstractProductListTerm implements ContentTermInterface
{
    /**
     * @var \Spryker\Shared\Content\ContentProductListType $type
     */
    protected $type;

    /**
     * @param \Spryker\Shared\Content\ContentProductListType $type
     */
    public function __construct(ContentProductListType $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getCategoryCandidateKey(): string
    {
        return $this->type->getCategoryCandidateKey();
    }

    /**
     * @return string
     */
    public function getTypeCandidateKey(): string
    {
        return $this->type->getCandidateKey();
    }

    /**
     * @return string
     */
    public function getCandidateKey(): string
    {
        return 'AbstractProductList';
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function execute(array $params): string
    {
        return '';
    }
}
