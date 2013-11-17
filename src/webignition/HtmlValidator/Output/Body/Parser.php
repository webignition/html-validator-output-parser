<?php

namespace webignition\HtmlValidator\Output\Body;

use webignition\HtmlValidator\Output\Body\Body;
use webignition\HtmlValidator\Output\Header\Header;

class Parser {
    
    const APPLICATION_JSON_CONTENT_TYPE = 'application/json';
    const TEXT_HTML_CONTENT_TYPE = 'text/html';

    public function parse(Header $header, $htmlValidatorBodyContent) {
        $body = new Body();
        
        switch ($header->get('content-type')->getTypeSubtypeString()) {
            case 'application/json':
                $body->setContent(json_decode($htmlValidatorBodyContent));
                break;

            case 'text/html':
                $body->setContent($this->getOutputObjectFromValidatorErrorContent($htmlValidatorBodyContent));
                break;     
            
            default:
                throw new \InvalidArgumentException('Invalid content type: ' . $header->get('content-type')->getTypeSubtypeString(), 1);
        }       
        
        return $body;
    }
    
    
    private function getOutputObjectFromValidatorErrorContent($htmlValidatorBodyContent) {        
        $dom = new \DOMDocument();
        $dom->loadHTML($htmlValidatorBodyContent);
        
        $errorContainers = $dom->getElementById('fatal-errors')->getElementsByTagName('li');
        
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