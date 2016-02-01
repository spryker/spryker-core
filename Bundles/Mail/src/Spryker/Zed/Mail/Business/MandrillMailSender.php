<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Mail\Business;

use Generated\Shared\Transfer\AttachmentTransfer;
use Generated\Shared\Transfer\MailHeaderTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\MailRecipientTransfer;
use Generated\Shared\Transfer\SendMailResponsesTransfer;
use Generated\Shared\Transfer\SendMailResponseTransfer;

class MandrillMailSender implements MailSenderInterface
{

    const STATUS_SENT = 'sent';
    const STATUS_QUEUED = 'queued';
    const STATUS_REJECTED = 'rejected';
    const STATUS_INVALID = 'invalid';

    /**
     * @var \Mandrill
     */
    protected $mandrill;

    /**
     * @var InclusionHandlerInterface
     */
    protected $inclusionHandler;

    /**
     * @param \Mandrill $mandrill
     * @param InclusionHandlerInterface $inclusionHandler
     */
    public function __construct(\Mandrill $mandrill, InclusionHandlerInterface $inclusionHandler)
    {
        $this->mandrill = $mandrill;
        $this->inclusionHandler = $inclusionHandler;
    }

    /**
     * @param MailTransfer $mailTransfer
     *
     * @return \Generated\Shared\Transfer\SendMailResponsesTransfer
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        $templateName = $mailTransfer->getTemplateName();
        $templateContent = $this->convertToJsonStyle($mailTransfer->getTemplateContent());
        $message = $this->extractMessage($mailTransfer);
        $async = $mailTransfer->getAsync();
        $ipPool = $mailTransfer->getIpPool();
        $sendAt = $mailTransfer->getSendAt();
        if ($sendAt !== null) {
            $sendAt = (new \DateTime($sendAt))->format('Y-m-d H:i:s');
        }

        $responses = (array) $this->mandrill->messages->sendTemplate($templateName, $templateContent, $message, $async, $ipPool, $sendAt);

        return $this->convertResponsesToTransfer($responses);
    }

    /**
     * @param array $responses
     *
     * @return \Generated\Shared\Transfer\SendMailResponsesTransfer
     */
    protected function convertResponsesToTransfer($responses)
    {
        $responsesTransfer = new SendMailResponsesTransfer();

        foreach ($responses as $response) {
            $responseTransfer = new SendMailResponseTransfer();
            $responseTransfer->setEmail($response['email']);
            $responseTransfer->setIsSent($response['status'] === self::STATUS_SENT);
            $responseTransfer->setIsQueued($response['status'] === self::STATUS_QUEUED);
            $responseTransfer->setIsRejected($response['status'] === self::STATUS_REJECTED);
            $responseTransfer->setIsInvalid($response['status'] === self::STATUS_INVALID);
            $responseTransfer->setRejectReason($response['reject_reason']);
            $responseTransfer->setIdMessage($response['_id']);

            $responsesTransfer->addResponse($responseTransfer);
        }

        return $responsesTransfer;
    }

    /**
     * @param SendMailResponsesTransfer $responses
     *
     * @return bool
     */
    public function isMailSent(SendMailResponsesTransfer $mailResponses)
    {
        foreach ($mailResponses->getResponses() as $response) {
            if ($response->getIsRejected() === true || $response->getIsInvalid() === true) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $templateContent
     *
     * @return array
     */
    protected function convertToJsonStyle($templateContent)
    {
        $result = [];
        foreach ($templateContent as $name => $content) {
            $result[] = [
                'name' => $name,
                'content' => $content,
            ];
        }

        return $result;
    }

    /**
     * @param MailTransfer $mailTransfer
     *
     * @return array
     */
    protected function extractMessage(MailTransfer $mailTransfer)
    {
        return [
            'subject' => $mailTransfer->getSubject(),
            'from_email' => $mailTransfer->getFromEmail(),
            'from_name' => $mailTransfer->getFromName(),
            'to' => $this->extractRecipients($mailTransfer->getRecipients()),
            'headers' => $this->extractHeaders($mailTransfer->getHeaders()),
            'important' => $mailTransfer->getImportant(),
            'track_opens' => $mailTransfer->getTrackOpens(),
            'track_clicks' => $mailTransfer->getTrackClicks(),
            'auto_text' => $mailTransfer->getAutoText(),
            'auto_html' => $mailTransfer->getAutoHtml(),
            'inline_css' => $mailTransfer->getInlineCss(),
            'url_strip_qs' => $mailTransfer->getUrlStripQueryString(),
            'preserve_recipients' => $mailTransfer->getPreserveRecipients(),
            'view_content_link' => $mailTransfer->getViewContentLink(),
            'bcc_address' => $mailTransfer->getBccAddress(),
            'tracking_domain' => $mailTransfer->getTrackingDomain(),
            'signing_domain' => $mailTransfer->getSigningDomain(),
            'return_path_domain' => $mailTransfer->getReturnPathDomain(),
            'merge' => $mailTransfer->getMerge(),
            'merge_language' => $mailTransfer->getMergeLanguage(),
            'global_merge_vars' => $this->convertToJsonStyle($mailTransfer->getGlobalMergeVars()),
            'merge_vars' => $this->extractMergeVars($mailTransfer->getRecipients()),
            'tags' => $mailTransfer->getTags(),
            'subaccount' => $mailTransfer->getSubAccount(),
            'google_analytics_domains' => $mailTransfer->getGoogleAnalyticsDomains(),
            'google_analytics_campaign' => $mailTransfer->getGoogleAnalyticsCampaign(),
            'metadata' => $mailTransfer->getMetadata(),
            'recipient_metadata' => $this->extractRecipientMetadata($mailTransfer->getRecipients()),
            'attachments' => $this->extractFiles($mailTransfer->getAttachments()),
            'images' => $this->extractFiles($mailTransfer->getImages()),
        ];
    }

    /**
     * @param \ArrayObject $recipients
     *
     * @return array
     */
    protected function extractRecipients(\ArrayObject $recipients)
    {
        $result = [];

        /** @var MailRecipientTransfer $recipient */
        foreach ($recipients as $recipient) {
            $result[] = [
                'email' => $recipient->getEmail(),
                'name' => $recipient->getName(),
                'type' => $recipient->getType(),
            ];
        }

        return $result;
    }

    /**
     * @param \ArrayObject $headers
     *
     * @return array
     */
    protected function extractHeaders(\ArrayObject $headers)
    {
        $result = [];

        /** @var MailHeaderTransfer $header */
        foreach ($headers as $header) {
            $result[$header->getKey()] = $header->getValue();
        }

        return $result;
    }

    /**
     * @param \ArrayObject $recipients
     *
     * @return array
     */
    protected function extractMergeVars(\ArrayObject $recipients)
    {
        $result = [];

        /** @var MailRecipientTransfer $recipient */
        foreach ($recipients as $recipient) {
            if (empty($recipient->getMergeVars())) {
                continue;
            }

            $result[] = [
                'rcpt' => $recipient->getEmail(),
                'vars' => $this->convertToJsonStyle($recipient->getMergeVars()),
            ];
        }

        return $result;
    }

    /**
     * @param \ArrayObject $recipientMetadata
     *
     * @return array
     */
    protected function extractRecipientMetadata(\ArrayObject $recipientMetadata)
    {
        $result = [];
        /** @var MailRecipientTransfer $individualData */
        foreach ($recipientMetadata as $individualData) {
            if (empty($individualData->getMetadata())) {
                continue;
            }

            $result[] = [
                'rcpt' => $individualData->getEmail(),
                'values' => $individualData->getMetadata(),
            ];
        }

        return $result;
    }

    /**
     * @param \ArrayObject $fileInfo
     *
     * @return array
     */
    protected function extractFiles(\ArrayObject $fileInfo)
    {
        $result = [];
        /** @var AttachmentTransfer $file */
        foreach ($fileInfo as $file) {
            $result[] = [
                'type' => $this->inclusionHandler->guessType($file->getFileName()),
                'name' => $file->getDisplayName() ? $file->getDisplayName() : $this->inclusionHandler->getFilename($file->getFileName()),
                'content' => $this->inclusionHandler->encodeBase64($file->getFileName()),
            ];
        }

        return $result;
    }

}
