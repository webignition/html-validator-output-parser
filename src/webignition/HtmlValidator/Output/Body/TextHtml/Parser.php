<?php

namespace webignition\HtmlValidator\Output\Body\TextHtml;

class Parser {  
    
    const SOFTWARE_ERROR_HEADING_CONTENT = 'Software error:';
    
    /**
     *
     * @var string
     */
    private $htmlValidatorBodyContent = null;
    
    /**
     *
     * @var \DOMDocument
     */
    private $dom = null;


    public function parse($htmlValidatorBodyContent) {
        $this->htmlValidatorBodyContent = $htmlValidatorBodyContent;
        
        if ($this->isValidatorSoftwareError()) {
            return $this->getValidatorInternalErrorOutputObject();
        }        
        
        if ($this->isCharacterEncodingError()) {            
            return $this->getCharacterEncodingErrorOutputObject();
        }        
        
        if ($this->hasFatalErrorsCollection()) {
            return $this->getFatalErrorCollectionOutputObject();
        }
        
        return $this->getValidatorUnknownErrorOutputObject();
    }
    
    
    /**
     * 
     * @return \DOMDocument
     */
    private function getDom() {
        if (is_null($this->dom)) {
            $this->dom = new \DOMDocument();
            $this->dom->loadHTML($this->htmlValidatorBodyContent);
        }
        
        return $this->dom;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function hasFatalErrorsCollection() {
        return !is_null($this->getFatalErrorsCollection());
    }
    
    
    /**
     * 
     * @return \DOMElement|null
     */
    private function getFatalErrorsCollection() {
        return $this->getDom()->getElementById('fatal-errors');
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isValidatorSoftwareError() {
        if (!$this->hasLevelOneHeading()) {
            return false;
        }
        
        return $this->getLevelOneHeading()->textContent === self::SOFTWARE_ERROR_HEADING_CONTENT;
    }
    
    
    /**
     * 
     * @return \DOMElement|null
     */
    private function getLevelOneHeading() {
        $levelOneHeadings = $this->getDom()->getElementsByTagName('h1');
        if (is_null($levelOneHeadings)) {
            return null;
        }
        
        return $levelOneHeadings->item(0);
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function hasLevelOneHeading() {
        return !is_null($this->getLevelOneHeading());
    }
    
    
    private function isCharacterEncodingError() {
        if (!$this->hasFatalErrorsCollection()) {
            return false;
        }
        
        $fatalErrorCollectionOutputObject = $this->getFatalErrorCollectionOutputObject();
        if (!isset($fatalErrorCollectionOutputObject->messages)) {
            return false;
        }
        
        $messages = $fatalErrorCollectionOutputObject->messages;
        if (count($messages) > 1) {
            return false;
        }
        
        $message = $messages[0];
        if (!isset($message->message)) {
            return false;
        }
        
        return substr_count($message->message, 'contained one or more bytes that I cannot interpret') === 1;
    }
    
    private function getValidatorInternalErrorOutputObject() {        
        $outputObject = new \stdClass();
        $outputObject->messages = array();      
        
        $currentError = new \stdClass();
        $currentError->message = 'Sorry, this document can\'t be checked';
        $currentError->type = 'error';
        $currentError->messageId = 'validator-internal-server-error';

        $outputObject->messages[] = $currentError;
        return $outputObject;       
    }    
    
    
    private function getCharacterEncodingErrorOutputObject() {
        if (!$this->isCharacterEncodingError()) {
            return null;
        }
        
        $fatalErrorCollectionOutputObject = $this->getFatalErrorCollectionOutputObject();             
        $message = $fatalErrorCollectionOutputObject->messages[0]->message;
        
        $outputObject = new \stdClass();
        $outputObject->messages = array();      
        
        $currentError = new \stdClass();
        $currentError->message = $message;
        $currentError->type = 'error';
        $currentError->messageId = 'character-encoding';

        $outputObject->messages[] = $currentError;
        return $outputObject;       
    }
    
    
    private function getValidatorUnknownErrorOutputObject() {
        $outputObject = new \stdClass();
        $outputObject->messages = array();      
        
        $currentError = new \stdClass();
        $currentError->message = 'An unknown error occurred';
        $currentError->type = 'error';
        $currentError->messageId = 'unknown';

        $outputObject->messages[] = $currentError;
        return $outputObject;            
    }
    
    
    /**
     * 
     * @return \stdClass
     */
    private function getFatalErrorCollectionOutputObject() {        
        $errorContainers = $this->getFatalErrorsCollection()->getElementsByTagName('li');
        
        $outputObject = new \stdClass();
        $outputObject->messages = array();
        
        foreach ($errorContainers as $errorContainer) {
            /* @var $errorContainer \DOMElement */
            $currentErrorContent = '';
            
            foreach ($errorContainer->childNodes as $childNode) {
                /* @var $childNode \DOMNode */
                if ($childNode->nodeType == XML_ELEMENT_NODE && $childNode->nodeName != 'span') {
                    $currentErrorContent .= $childNode->ownerDocument->saveHTML($childNode);
                }
            }
            
            $currentError = new \stdClass();
            $currentError->message = $currentErrorContent;
            $currentError->type = 'error';
            
            $outputObject->messages[] = $currentError;
        }     
        
        return $outputObject;        
    }
}