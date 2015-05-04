<?php


namespace SprykerFeature\Zed\Mail\Business;


use Generated\Shared\Transfer\MailMailTransferTransfer;

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
     * @param MailTransfer $mailTransfer
     *
     * @return array
     * @throws \Mandrill_Error
     */
    public function sendMail(MailTransfer $mailTransfer)
    {
        $templateName = $mailTransfer->getTemplateName();
        $templateContent = $this->convertToJsonStyle($mailTransfer->getTemplateContent());
        $message = $this->extractMessage($mailTransfer);
        $async = $mailTransfer->isAsync();
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
            'to' => $mailTransfer->getRecipients(),
            'headers' => $mailTransfer->getHeaders(),
            'important' => $mailTransfer->isImportant(),
            'track_opens' => $mailTransfer->isTrackOpens(),
            'track_clicks' => $mailTransfer->isTrackClicks(),
            'auto_text' => $mailTransfer->isAutoText(),
            'auto_html' => $mailTransfer->isAutoHtml(),
            'inline_css' => $mailTransfer->isInlineCss(),
            'url_strip_qs' => $mailTransfer->isUrlStripQueryString(),
            'preserve_recipients' => $mailTransfer->isPreserveRecipients(),
            'view_content_link' => $mailTransfer->isViewContentLink(),
            'bcc_address' => $mailTransfer->getBccAddress(),
            'tracking_domain' => $mailTransfer->getTrackingDomain(),
            'signing_domain' => $mailTransfer->getSigningDomain(),
            'return_path_domain' => $mailTransfer->getReturnPathDomain(),
            'merge' => $mailTransfer->isMerge(),
            'merge_language' => $mailTransfer->getMergeLanguage(),
            'global_merge_vars' => $this->convertToJsonStyle($mailTransfer->getGlobalMergeVars()),
            'merge_vars' => $this->extractMergeVars($mailTransfer->getRecipientMergeVars()),
            'tags' => $mailTransfer->getTags(),
            'subaccount' => $mailTransfer->getSubAccount(),
            'google_analytics_domains' => $mailTransfer->getGoogleAnalyticsDomains(),
            'google_analytics_campaign' => $mailTransfer->getGoogleAnalyticsCampaign(),
            'metadata' => $mailTransfer->getMetadata(),
            'recipient_metadata' => $this->extractRecipientMetadata($mailTransfer->getRecipientMetadata()),
            'attachments' => $this->extractFiles($mailTransfer->getAttachments()),
            'images' => $this->extractFiles($mailTransfer->getImages())
        ];
    }

    /**
     * @param array $mergeVars
     * @return array
     */
    protected function extractMergeVars(array $mergeVars)
    {
        $result = [];

        foreach ($mergeVars as $recipientVars) {
            $result[] = [
                'rcpt' => $recipientVars['recipient'],
                'vars' => $this->convertToJsonStyle($recipientVars['vars'])
            ];
        }

        return $result;
    }

    /**
     * @param array $recipientMetadata
     *
     * @return array
     */
    protected function extractRecipientMetadata(array $recipientMetadata)
    {
        $result = [];
        foreach ($recipientMetadata as $individualData) {
            $result[] = [
                'rcpt' => $individualData['recipient'],
                'values' => $individualData['vars']
            ];
        }

        return $result;
    }

    /**
     * @param array $fileInfo
     *
     * @return array
     */
    protected function extractFiles(array $fileInfo)
    {
        $result = [];
        foreach ($fileInfo as $file) {
            $result[] = [
                'type' => $this->inclusionHandler->guessType($file['path']),
                'name' => $file['name'] ? $file['name'] : $this->inclusionHandler->getFilename($file['path']),
                'content' => $this->inclusionHandler->encodeBase64($file['path'])
            ];
        }

        return $result;
    }
}
