<?php

namespace SprykerTest\Zed\Discount\Presentation\PageObject;

use SprykerTest\Zed\Discount\PresentationTester;

class DiscountEditPage
{

    const URL = '/discount/index/edit';

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
     * @param \SprykerTest\Zed\Discount\Presentation\PageObject\DiscountCreatePage $createPage
     */
    public function __construct(PresentationTester $i, DiscountCreatePage $createPage)
    {
        $this->tester = $i;
        $this->createPage = $createPage;
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
