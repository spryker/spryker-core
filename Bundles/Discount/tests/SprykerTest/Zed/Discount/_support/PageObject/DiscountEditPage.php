<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\PageObject;

use SprykerTest\Zed\Discount\DiscountPresentationTester;

class DiscountEditPage
{
    public const URL = '/discount/index/edit';

    /**
     * @var \SprykerTest\Zed\Discount\DiscountPresentationTester
     */
    protected $tester;

    /**
     * @var \SprykerTest\Zed\Discount\PageObject\DiscountCreatePage
     */
    protected $createPage;

    /**
     * @param \SprykerTest\Zed\Discount\DiscountPresentationTester $i
     * @param \SprykerTest\Zed\Discount\PageObject\DiscountCreatePage $createPage
     */
    public function __construct(DiscountPresentationTester $i, DiscountCreatePage $createPage)
    {
        $this->tester = $i;
        $this->createPage = $createPage;
    }

    /**
     * @param int|string $identifier
     *
     * @return void
     */
    public function open($identifier)
    {
        $this->tester->amOnPage($this->url($identifier));
    }

    /**
     * @param int|string $identifier
     *
     * @return string
     */
    public function url($identifier)
    {
        return static::URL . "?id-discount=$identifier";
    }
}
