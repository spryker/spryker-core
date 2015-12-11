<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

class InValidDependencyContainer
{

    public function fooBar()
    {
        new self();
        new self();
    }

}
