<?php
namespace Acceptance\Discount\Zed\PageObject;

use ZedAcceptanceTester;

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

    public function __construct(ZedAcceptanceTester $i, DiscountCreatePage $createPage)
    {
        $this->tester = $i;
        $this->createPage = $createPage;
    }

    /**
     * @return void
     */
    public function open($id)
    {
        $this->tester->amOnPage($this->url($id));
    }

    public function url($id)
    {
        return static::URL . "?id-discount=$id";
    }

}