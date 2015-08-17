<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Product\Communication\Form\ProductImportForm;
use Symfony\Component\HttpFoundation\Request;

class ImportController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->viewResponse(['form' => new ProductImportForm()]);
    }

    /**
     * @param Request $request
     *
     * @throws \Zend_Form_Exception
     *
     * @return RedirectResponse
     */
    public function saveAction(Request $request)
    {
        $importForm = new ProductImportForm();
        if ($request->getMethod() === 'POST' && $importForm->isValid()) {
            $uploadedFile = $this->facadeProduct->importUploadedFile(
                ProductImportForm::FORM_ELEMENT_IMPORT_FILE_NAME
            );
            $result = $this->facadeProduct->importProductsFromFile($uploadedFile);
            $this->addSuccessMessage(
                sprintf(
                    'Uploaded: %d' . PHP_EOL . 'Successful: %d' . PHP_EOL . 'Failed:    %d',
                    $result->getTotalCount(),
                    $result->getSuccessCount(),
                    $result->getFailedCount()
                )
            );
        } else {
            // TODO addError only except strings getErrorMessages returns an array
//            $this->addErrorMessage($importForm->getErrorMessages());
        }

        return $this->redirectResponse('/product/import');
    }

}
