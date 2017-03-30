<?php
namespace Acceptance\Discount\Zed\PageObject;

use ZedAcceptanceTester;

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

    public function __construct(ZedAcceptanceTester $i)
    {
        $this->tester = $i;
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