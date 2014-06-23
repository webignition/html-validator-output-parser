<?php

namespace webignition\HtmlValidator\Output\Parser;

use webignition\HtmlValidator\Output\Header\Parser as HeaderParser;
use webignition\HtmlValidator\Output\Body\Parser as BodyParser;
use webignition\HtmlValidator\Output\Output;

class Parser {    
    
    public function parse($htmlValidatorOutput) {        
        $headerBodyParts = explode("\n\n", str_replace("\r\n", "\n", $htmlValidatorOutput), 2);       
        
        $headerParser = new HeaderParser();
        $header = $headerParser->parse($headerBodyParts[0]);
        
        $bodyParser = new BodyParser();        
        $body = $bodyParser->parse($header, $headerBodyParts[1]);
        
        $output = new Output($header, $body);
        
        return $output;
    }
    
}