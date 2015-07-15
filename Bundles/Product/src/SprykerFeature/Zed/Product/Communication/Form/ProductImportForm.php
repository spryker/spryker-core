<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Communication\Form;

use SprykerFeature\Zed\Library\Form;

/**
 * Class Import
 */
class ProductImportForm extends Form
{

    const FORM_IDENTIFIER = 'productImportForm';
    const FORM_ELEMENT_IMPORT_FILE_NAME = 'importFile';
    const FORM_ACTION = '/product/import/save/';
    const FORM_ERROR_MESSAGE_DEFAULT = '"Product Import/Update" failure: Invalid file';

    /**
     * @throws \Zend_Form_Exception
     */
    public function __construct()
    {
        parent::__construct();

        $uploadFile = new \Zend_Form_Element_File(self::FORM_ELEMENT_IMPORT_FILE_NAME);
        $maxFileSize = ini_get('upload_max_filesize');
        $maxFileSizeInBytes = $maxFileSize * 1024 * 1024;
        $uploadFile->setRequired(true);
        $uploadFile->setMaxFileSize($maxFileSizeInBytes);
        $uploadFile->addValidator('Extension', false, 'csv')
            ->addValidator('Size', false, ['max' => $maxFileSizeInBytes]);

        $this->addElement($uploadFile);

        //set name, we need it in order to use it on document.<formName>.submit() on the save callToAction
        //that one looks much nicer than the standard save form button
        // yeah i know about css and stuff but i only got this implementation
        $this->setName(self::FORM_IDENTIFIER);
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        $errorMessage = self::FORM_ERROR_MESSAGE_DEFAULT . PHP_EOL;
        $erroneousElements = $this->getMessages();
        foreach ($erroneousElements as $element) {
            foreach ($element as $message) {
                $errorMessage .= $message . PHP_EOL;
            }
        }

        return $errorMessage;
    }

    /**
     * @return string
     */
    public function getFormElementImportFileName()
    {
        return self::FORM_ELEMENT_IMPORT_FILE_NAME;
    }

}
