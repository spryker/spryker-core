<?php
/**
 * Created by PhpStorm.
 * User: karl
 * Date: 11.05.15
 * Time: 10:58
 */

namespace Unit\SprykerEngine\Zed\Translation\Business\Model;


use SprykerEngine\Zed\Translation\Business\Model\TranslationFile;

class TranslationFileTest extends \PHPUnit_Framework_TestCase
{
    public function testSetPathShouldReturnTranslationFile()
    {
        $translationFile = new TranslationFile();

        $this->assertInstanceOf(
            'SprykerEngine\Zed\Translation\Business\Model\TranslationFile',
            $translationFile->setPath('/some/path')
        );
    }

    public function testSetLocaleShouldReturnTranslationFile()
    {
        $translationFile = new TranslationFile();

        $this->assertInstanceOf(
            'SprykerEngine\Zed\Translation\Business\Model\TranslationFile',
            $translationFile->setLocale('de')
        );
    }

    public function testSetFormatShouldReturnTranslationFile()
    {
        $translationFile = new TranslationFile();

        $this->assertInstanceOf(
            'SprykerEngine\Zed\Translation\Business\Model\TranslationFile',
            $translationFile->setFormat('po')
        );
    }

}