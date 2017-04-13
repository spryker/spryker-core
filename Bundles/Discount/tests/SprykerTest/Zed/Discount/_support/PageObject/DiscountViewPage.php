<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Discount\PageObject;

use SprykerTest\Zed\Discount\PresentationTester;

class DiscountViewPage
{

    const URL = '/discount/index/view';

    /**
     * @var \ZedAcceptanceTester
     */
    protected $tester;

    /**
     * @var \SprykerTest\Zed\Discount\PageObject\DiscountCreatePage
     */
    protected $createPage;

    /**
     * @param \SprykerTest\Zed\Discount\PresentationTester $i
     */
    public function __construct(PresentationTester $i)
    {
        $this->tester = $i;
    }

    /**
     * @param $identifier
     *
     * @return void
     */
    public function open($identifier)
    {
        $this->tester->amOnPage($this->url($identifier));
    }

    /**
     * @param $identifier
     *
     * @return string
     */
    public function url($identifier)
    {
        return static::URL . "?id-discount=$identifier";
    }

}
