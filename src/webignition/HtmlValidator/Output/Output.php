<?php

namespace webignition\HtmlValidator\Output;

use webignition\HtmlValidator\Output\Header\Header;
use webignition\HtmlValidator\Output\Body\Body;

class Output {
    
    const STATUS_VALID = 'Valid';
    
    
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
        return $this->header->get('status') === self::STATUS_VALID;
    }
    
}