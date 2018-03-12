<?php

namespace webignition\HtmlValidator\Output\Body;

class Body {
    
    /**
     *
     * @var \stdClass
     */
    private $content = null;
    
    
    /**
     * 
     * @param \stdClass $content
     * @return \webignition\HtmlValidator\Output\Body\Body
     */
    public function setContent(\stdClass $content) {
        $this->content = $content;
        return $this;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasContent() {
        return !is_null($this->content);
    }
    
    
    /**
     * 
     * @return \stdClass
     */
    public function getContent() {
        return $this->content;
    }
    
    
    /**
     * 
     * @return array
     */
    public function getMessages() {
        if (!$this->hasContent()) {
            return array();
        }
        
        return $this->getContent()->messages;
    }
    
}