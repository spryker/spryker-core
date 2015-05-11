<?php

namespace SprykerEngine\Zed\Translation\Business;

interface TranslatorInterface extends \Symfony\Component\Translation\TranslatorInterface
{
    /**
     * @param $id
     *
     * @return bool
     */
    public function has($id);
}