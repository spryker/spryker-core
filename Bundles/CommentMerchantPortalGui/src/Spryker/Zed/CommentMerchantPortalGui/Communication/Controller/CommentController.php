<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\CommentMerchantPortalGui\Communication\CommentMerchantPortalGuiCommunicationFactory getFactory()
 */
class CommentController extends AbstractController
{
    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_ADD_COMMENT_FORM = 'add-comment-form';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID_UPDATE_COMMENT_FORM = 'update-comment-form';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_TOKEN = '_token';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function createAction(Request $request): JsonResponse
    {
        $tokenValue = (string)$request->get(static::REQUEST_PARAMETER_TOKEN);
        $validationResponseTransfer = $this->getFactory()
            ->createCsrfTokenValidator()
            ->validate(static::CSRF_TOKEN_ID_ADD_COMMENT_FORM, $tokenValue);
        if (!$validationResponseTransfer->getIsSuccessOrFail()) {
            return $this->createErrorJsonResponse($validationResponseTransfer->getErrorMessages());
        }

        $commentRequestTransfer = $this->createCommentRequestTransfer($request);
        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentFacade()
            ->addComment($commentRequestTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessfulOrFail()) {
            return $this->createErrorJsonResponse($commentThreadResponseTransfer->getMessages());
        }

        $commentTransfer = $this->getLastComment($commentThreadResponseTransfer->getCommentThreadOrFail());

        return $this->jsonResponse([
            'comment' => $commentTransfer->toArray(),
            'csrfToken' => $this->createUpdateCommentFormCsrfToken(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Request $request): JsonResponse
    {
        $tokenValue = (string)$request->get(static::REQUEST_PARAMETER_TOKEN);
        $validationResponseTransfer = $this->getFactory()
            ->createCsrfTokenValidator()
            ->validate(static::CSRF_TOKEN_ID_UPDATE_COMMENT_FORM, $tokenValue);
        if (!$validationResponseTransfer->getIsSuccessOrFail()) {
            return $this->createErrorJsonResponse($validationResponseTransfer->getErrorMessages());
        }

        $commentRequestTransfer = $this->createCommentRequestTransfer($request);
        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentFacade()
            ->updateComment($commentRequestTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessfulOrFail()) {
            return $this->createErrorJsonResponse($commentThreadResponseTransfer->getMessages());
        }

        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = $this->findCommentByUuid(
            $commentThreadResponseTransfer->getCommentThreadOrFail(),
            $commentRequestTransfer->getCommentOrFail()->getUuidOrFail(),
        );

        return $this->jsonResponse([
            'comment' => $commentTransfer->toArray(),
            'csrfToken' => $this->createUpdateCommentFormCsrfToken(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAction(Request $request): JsonResponse
    {
        $tokenValue = (string)$request->get(static::REQUEST_PARAMETER_TOKEN);
        $validationResponseTransfer = $this->getFactory()
            ->createCsrfTokenValidator()
            ->validate(static::CSRF_TOKEN_ID_UPDATE_COMMENT_FORM, $tokenValue);
        if (!$validationResponseTransfer->getIsSuccessOrFail()) {
            return $this->createErrorJsonResponse($validationResponseTransfer->getErrorMessages());
        }

        $commentRequestTransfer = $this->createCommentRequestTransfer($request);
        $commentThreadResponseTransfer = $this->getFactory()
            ->getCommentFacade()
            ->removeComment($commentRequestTransfer);

        if (!$commentThreadResponseTransfer->getIsSuccessfulOrFail()) {
            return $this->createErrorJsonResponse($commentThreadResponseTransfer->getMessages());
        }

        return $this->jsonResponse();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CommentRequestTransfer
     */
    protected function createCommentRequestTransfer(Request $request): CommentRequestTransfer
    {
        $merchantUserTransfer = $this->getFactory()
            ->getMerchantUserFacade()
            ->getCurrentMerchantUser();

        $commentTransfer = (new CommentTransfer())
            ->fromArray($request->request->all(), true)
            ->setUser($merchantUserTransfer->getUserOrFail())
            ->setFkUser($merchantUserTransfer->getUserOrFail()->getIdUserOrFail());

        return (new CommentRequestTransfer())
            ->fromArray($request->request->all(), true)
            ->setComment($commentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    protected function getLastComment(CommentThreadTransfer $commentThreadTransfer): CommentTransfer
    {
        $commentTransfers = $commentThreadTransfer->getComments()->getArrayCopy();
        /** @var \Generated\Shared\Transfer\CommentTransfer $commentTransfer */
        $commentTransfer = end($commentTransfers);

        return $commentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     * @param string $commentUuid
     *
     * @return \Generated\Shared\Transfer\CommentTransfer|null
     */
    protected function findCommentByUuid(CommentThreadTransfer $commentThreadTransfer, string $commentUuid): ?CommentTransfer
    {
        foreach ($commentThreadTransfer->getComments() as $commentTransfer) {
            if ($commentTransfer->getUuidOrFail() === $commentUuid) {
                return $commentTransfer;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    protected function createUpdateCommentFormCsrfToken(): string
    {
        return $this->getFactory()
            ->getCsrfTokenManager()
            ->getToken(static::CSRF_TOKEN_ID_UPDATE_COMMENT_FORM);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MessageTransfer> $messageTransfers
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createErrorJsonResponse(ArrayObject $messageTransfers): JsonResponse
    {
        return $this->jsonResponse([
            'messages' => $this->getFactory()
                ->createMessageTranslator()
                ->translateErrorMessages($messageTransfers),
        ], Response::HTTP_BAD_REQUEST);
    }
}
