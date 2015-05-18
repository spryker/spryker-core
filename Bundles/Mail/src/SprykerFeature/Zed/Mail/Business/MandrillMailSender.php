<?php

namespace SprykerFeature\Zed\Mail\Business;

use Generated\Shared\Transfer\MailAttachmentTransfer;
use Generated\Shared\Transfer\MailHeaderTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\MailRecipientTransfer;
use SprykerEngine\Shared\Transfer\TransferArrayObject;

class MandrillMailSender implements MailSenderInterface
{
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
     * @param MailMailTransfer $mailTransfer
     *
     * @return array
     * @throws \Mandrill_Error
     */
    public function sendMail(MailMailTransfer $mailTransfer)
    {
        $templateName = $mailTransfer->getTemplateName();
        $templateContent = $this->convertToJsonStyle($mailTransfer->getTemplateContent());
        $message = $this->extractMessage($mailTransfer);
        $async = $mailTransfer->getAsync();
        $ipPool = $mailTransfer->getIpPool();
        $sendAt = $mailTransfer->getSendAt();
        $sendAtString = $sendAt ? $sendAt->format('Y-m-d H:i:s') : null;

        return $this->mandrill->messages->sendTemplate($templateName, $templateContent, $message, $async, $ipPool, $sendAtString);
    }

    /**
     * @param array $templateContent
     *
     * @return array
     */
    protected function convertToJsonStyle(array $templateContent)
    {
        $result = [];
        foreach ($templateContent as $name => $content) {
            $result[] = [
                'name' => $name,
                'content' => $content
            ];
        }

        return $result;
    }

    /**
     * @param MailMailTransfer $mailTransfer
     *
     * @return array
     */
    protected function extractMessage(MailMailTransfer $mailTransfer)
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
            'images' => $this->extractFiles($mailTransfer->getImages())
        ];
    }

    /**
     * @param TransferArrayObject $recipients
     *
     * @return array
     */
    protected function extractRecipients(TransferArrayObject $recipients)
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
     * @param TransferArrayObject $headers
     *
     * @return array
     */
    protected function extractHeaders(TransferArrayObject $headers)
    {
        $result = [];

        /** @var MailHeaderTransfer $header */
        foreach ($headers as $header) {
            $result[$header->getKey()] = $header->getValue();
        }

        return $result;
    }

    /**
     * @param TransferArrayObject $recipients
     *
     * @return array
     */
    protected function extractMergeVars(TransferArrayObject $recipients)
    {
        $result = [];

        /** @var MailRecipientTransfer $recipient */
        foreach ($recipients as $recipient) {
            if (empty($recipient->getMergeVars())) {
                continue;
            }

            $result[] = [
                'rcpt' => $recipient->getEmail(),
                'vars' => $this->convertToJsonStyle($recipient->getMergeVars())
            ];
        }

        return $result;
    }

    /**
     * @param TransferArrayObject $recipientMetadata
     *
     * @return array
     */
    protected function extractRecipientMetadata(TransferArrayObject $recipientMetadata)
    {
        $result = [];
        /** @var MailRecipientTransfer $individualData */
        foreach ($recipientMetadata as $individualData) {
            if (empty($individualData->getMetadata())) {
                continue;
            }

            $result[] = [
                'rcpt' => $individualData->getEmail(),
                'values' => $individualData->getMetadata()
            ];
        }

        return $result;
    }

    /**
     * @param TransferArrayObject $fileInfo
     *
     * @return array
     */
    protected function extractFiles(TransferArrayObject $fileInfo)
    {
        $result = [];
        /** @var MailAttachmentTransfer $file */
        foreach ($fileInfo as $file) {
            $result[] = [
                'type' => $this->inclusionHandler->guessType($file->getFileName()),
                'name' => $file->getDisplayName() ? $file->getDisplayName() : $this->inclusionHandler->getFilename($file->getFileName()),
                'content' => $this->inclusionHandler->encodeBase64($file->getFileName())
            ];
        }

        return $result;
    }
}
