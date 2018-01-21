<?php

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Generated\Shared\Transfer\FileInfoTransfer;
use Generated\Shared\Transfer\FileManagerSaveRequestTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CmsGui\Communication\Controller\CreateGlossaryController;
use Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory;
use Spryker\Zed\FileManagerGui\Communication\Form\FileForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method FileManagerGuiCommunicationFactory getFactory()
 * @method getFacade()
 */
class AddController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function indexAction(Request $request)
    {
//        $dataProvider = $this->getFactory()->createFileFormDataProvider();
        $form = $this->getFactory()
            ->createFileForm()
            ->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $saveRequestTransfer = $this->createFileManagerSaveRequestTransfer($data);

            $this->getFactory()->getFileManagerFacade()->save($saveRequestTransfer);

            $redirectUrl = Url::generate('/file-manager-gui')->build();

            return $this->redirectResponse($redirectUrl);
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
        ]);
    }

    /**
     * @param array $data
     * @return FileManagerSaveRequestTransfer
     */
    protected function createFileManagerSaveRequestTransfer(array $data)
    {
        $requestTransfer = new FileManagerSaveRequestTransfer();
        $requestTransfer->setFile($this->createFileTransfer($data));
        $requestTransfer->setFileInfo($this->createFileInfoTransfer($data));
        $requestTransfer->setContent($this->getFileContent($data));

        return $requestTransfer;
    }

    /**
     * @param array $data
     * @return FileInfoTransfer
     */
    protected function createFileInfoTransfer(array $data)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $data[FileForm::FIELD_FILE_CONTENT];
        $fileInfo = new FileInfoTransfer();

        $fileInfo->setFileExtension($uploadedFile->getClientOriginalExtension());
        $fileInfo->setSize($uploadedFile->getSize());
        $fileInfo->setType($uploadedFile->getMimeType());

        return $fileInfo;
    }

    /**
     * @param array $data
     * @return FileTransfer
     */
    protected function createFileTransfer(array $data)
    {
        $file = new FileTransfer();
        $file->setFileName($data[FileForm::FIELD_FILE_NAME]);

        return $file;
    }

    /**
     * @param array $data
     * @return bool|string
     */
    protected function getFileContent(array $data)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $data[FileForm::FIELD_FILE_CONTENT];

        return file_get_contents($uploadedFile->getRealPath());
    }

}