<?php

namespace SprykerFeature\Zed\Glossary\Business\Key;

interface KeySourceInterface
{
    /**
     * @return array
     */
    public function retrieveKeyArray();
}
