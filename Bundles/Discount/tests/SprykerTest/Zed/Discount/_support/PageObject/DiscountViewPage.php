<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\PageObject;

use SprykerTest\Zed\Discount\DiscountPresentationTester;

class DiscountViewPage
{
    public const URL = '/discount/index/view';

    /**
     * @var \SprykerTest\Zed\Discount\DiscountPresentationTester|\SprykerTest\Zed\Discount\PageObject\DiscountPresentationTester
     */
    protected $tester;

    /**
     * @var \SprykerTest\Zed\Discount\PageObject\DiscountCreatePage
     */
    protected $createPage;

    /**
     * @param \SprykerTest\Zed\Discount\DiscountPresentationTester $i
     */
    public function __construct(DiscountPresentationTester $i)
    {
        $this->tester = $i;
    }

    /**
     * @param string $identifier
     *
     * @return void
     */
    public function open($identifier)
    {
        $this->tester->amOnPage($this->url($identifier));
    }

    /**
     * @param string $identifier
     *
     * @return string
     */
    public function url($identifier)
    {
        return static::URL . "?id-discount=$identifier";
    }
}
