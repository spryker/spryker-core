<?php
namespace Spryker\Zed\Product\Business\Product\Validity;

interface ValidityUpdaterInterface
{
    /**
     * @return void
     */
    public function checkAndTouchAllProducts();

}
