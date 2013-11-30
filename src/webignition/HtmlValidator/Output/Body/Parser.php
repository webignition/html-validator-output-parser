<?php

namespace webignition\HtmlValidator\Output\Body;

use webignition\HtmlValidator\Output\Body\Body;
use webignition\HtmlValidator\Output\Header\Header;
use webignition\HtmlValidator\Output\Body\TextHtml\Parser as TextHtmlBodyParser;

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
                $textHtmlParser = new TextHtmlBodyParser();
                $body->setContent($textHtmlParser->parse($htmlValidatorBodyContent));  
                break;     
            
            default:
                throw new \InvalidArgumentException('Invalid content type: ' . $header->get('content-type')->getTypeSubtypeString(), 1);
        }       
        
        return $body;
    }
}