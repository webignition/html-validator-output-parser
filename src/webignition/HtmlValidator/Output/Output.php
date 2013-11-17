<?php

namespace webignition\HtmlValidator\Output;

use webignition\HtmlValidator\Output\Header\Header;
use webignition\HtmlValidator\Output\Body\Body;

class Output {
    
    const STATUS_VALID = 'Valid';
    const STATUS_ABORT = 'Abort';
    const TYPE_ERROR = 'error';
    
    
    /**
     *
     * @var Header 
     */
    private $header;
    
    /**
     *
     * @var Body
     */
    private $body;
    
    
    
    /**
     * 
     * @param \webignition\HtmlValidator\Output\Header\Header $header
     * @param \webignition\HtmlValidator\Output\Body\Body $body
     */
    public function __construct(Header $header, Body $body) {
        $this->header = $header;
        $this->body = $body;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getMessages() {        
        return $this->body->getMessages();
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isValid() {
        $status = $this->header->get('status');
        if ($status == self::STATUS_ABORT) {
            return null;
        }
        
        return $status === self::STATUS_VALID;
    }
    
    
    /**
     * 
     * @return int
     */
    public function getErrorCount() {
        $errorCount = 0;
        
        foreach ($this->getMessages() as $message) {
            if (isset($message->type) && $message->type == self::TYPE_ERROR) {
                $errorCount++;
            }
        }
        
        return $errorCount;
    }
    
}