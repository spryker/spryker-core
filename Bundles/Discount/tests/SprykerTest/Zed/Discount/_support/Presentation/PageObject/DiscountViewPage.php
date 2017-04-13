<?php

namespace SprykerTest\Zed\Discount\Presentation\PageObject;

use SprykerTest\Zed\Discount\PresentationTester;

class DiscountViewPage
{

    const URL = '/discount/index/view';

    /**
     * @var \ZedAcceptanceTester
     */
    protected $tester;

    /**
     * @var \Acceptance\Discount\Zed\PageObject\DiscountCreatePage
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
     * @param int|string $id
     *
     * @return void
     */
    public function open($id)
    {
        $this->tester->amOnPage($this->url($id));
    }

    /**
     * @param int|string $id
     *
     * @return string
     */
    public function url($id)
    {
        return static::URL . "?id-discount=$id";
    }

}
