<?php

namespace webignition\HtmlValidator\Output;

use webignition\HtmlValidator\Output\Header\Header;
use webignition\HtmlValidator\Output\Body\Body;

class Output
{
    const STATUS_VALID = 'Valid';
    const STATUS_ABORT = 'Abort';
    const TYPE_ERROR = 'error';

    const VALIDATOR_INTERNAL_SERVER_ERROR_MESSAGE_ID = 'validator-internal-server-error';

    /**
     * @var Header
     */
    private $header;

    /**
     * @var Body
     */
    private $body;

    /**
     * @param Header $header
     * @param Body $body
     */
    public function __construct(Header $header, Body $body)
    {
        $this->header = $header;
        $this->body = $body;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->body->getMessages();
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $status = $this->header->get('status');
        if (is_null($status) || $status == self::STATUS_ABORT) {
            return null;
        }

        return $status === self::STATUS_VALID;
    }

    /**
     * @return bool
     */
    public function wasAborted()
    {
        $status = $this->header->get('status');
        return is_null($status) || $status == self::STATUS_ABORT;
    }

    /**
     * @return int
     */
    public function getErrorCount()
    {
        $errorCount = 0;

        foreach ($this->getMessages() as $message) {
            if (isset($message->messageId) && $message->messageId == self::VALIDATOR_INTERNAL_SERVER_ERROR_MESSAGE_ID) {
                return null;
            }

            if (isset($message->type) && $message->type == self::TYPE_ERROR) {
                $errorCount++;
            }
        }

        return $errorCount;
    }
}
