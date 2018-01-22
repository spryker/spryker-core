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
class EditController extends AbstractController
{

    const URL_PARAM_ID_FILE = 'id-file';

    /**
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    public function indexAction(Request $request)
    {
        $idFile = $request->get(static::URL_PARAM_ID_FILE);
        $dataProvider = $this->getFactory()->createFileFormDataProvider();
        $form = $this->getFactory()
            ->createFileForm($dataProvider->getData($idFile))
            ->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $saveRequestTransfer = $this->createFileManagerSaveRequestTransfer($data);

            $this->getFactory()->getFileManagerFacade()->save($saveRequestTransfer);

            $redirectUrl = Url::generate(sprintf('/file-manager-gui/edit?id-file=%d', $idFile))->build();

            return $this->redirectResponse($redirectUrl);
        }

        $fileInfoTable = $this->getFactory()->createFileInfoTable($idFile);
        $fileFormsTabs = $this->getFactory()->createFileFormTabs();

        return [
            'fileFormTabs' => $fileFormsTabs->createView(),
            'fileInfoTable' => $fileInfoTable->render(),
            'fileForm' => $form->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
        ];
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function fileInfoTableAction(Request $request)
    {
        $idFile = $this->castId(
            $request->get(static::URL_PARAM_ID_FILE)
        );

        $fileInfoTable = $this
            ->getFactory()
            ->createFileInfoTable($idFile);

        return $this->jsonResponse(
            $fileInfoTable->fetchData()
        );
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

        if ($uploadedFile === null) {
            return $fileInfo;
        }

        $fileInfo->setFileExtension($uploadedFile->getClientOriginalExtension());
        $fileInfo->setSize($uploadedFile->getSize());
        $fileInfo->setType($uploadedFile->getMimeType());
        $fileInfo->setFkFile($data[FileForm::FIELD_ID_FILE]);

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
        $file->setIdFile($data[FileForm::FIELD_ID_FILE]);

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

        if ($uploadedFile === null) {
            return null;
        }

        return file_get_contents($uploadedFile->getRealPath());
    }

}