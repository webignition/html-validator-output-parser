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

//            case 'text/html':                
//                TBC: need to parse out validator-level error from html response
//                break;     
            
            default:
                throw new \InvalidArgumentException('Invalid content type: ' . $header->get('content-type')->getTypeSubtypeString(), 1);
        }       
        
        return $body;
    }
}