<?php

namespace Discount\PageObject;

use Discount\ZedPresentationTester;

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
     * @param \Discount\ZedPresentationTester $i
     * @param \Discount\PageObject\DiscountCreatePage $createPage
     */
    public function __construct(ZedPresentationTester $i, DiscountCreatePage $createPage)
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
