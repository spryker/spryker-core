<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

class InValidFactory
{

    public function fooBar()
    {
        new self();
        new self();
    }

}
