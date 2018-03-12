<?php

namespace webignition\HtmlValidator\Output\Header;

use webignition\InternetMediaType\Parser\Parser as MediaTypeParser;

class Parser {
    
    const W3C_VALIDATOR_HEADER_PREFIX = 'X-W3C-Validator-';

    public function parse($htmlValidatorHeaderContent) {
        $header = new Header();
        
        $headerLines = explode("\n", $htmlValidatorHeaderContent);
        
        foreach ($headerLines as $headerLine) {
            $keyValueParts = explode(':', $headerLine, 2);
            
            if ($this->isContentTypeHeader($keyValueParts[0])) {
                $mediaTypeParser = new MediaTypeParser();
                $header->set($keyValueParts[0], $mediaTypeParser->parse($keyValueParts[1]));
            }
            
            if ($this->isW3cValidatorHeaderKey($keyValueParts[0])) {
                $header->set($this->getKeyFromW3cKey($keyValueParts[0]), $this->getTypedValueFromW3cValue(trim($keyValueParts[1])));
            }
        }
        
        return $header;
    }
    
    
    /**
     * 
     * @param string $key
     * @return boolean
     */
    private function isW3cValidatorHeaderKey($key) {
        return substr($key, 0, strlen(self::W3C_VALIDATOR_HEADER_PREFIX)) == self::W3C_VALIDATOR_HEADER_PREFIX;
    }
    
    
    /**
     * 
     * @param string $w3cKey
     * @return string
     */
    private function getKeyFromW3cKey($w3cKey) {
        return lcfirst(str_replace(self::W3C_VALIDATOR_HEADER_PREFIX, '', $w3cKey));
    }
    
    
    /**
     * 
     * @param string $w3cValue
     * @return mixed
     */
    private function getTypedValueFromW3cValue($w3cValue) {        
        if (ctype_digit($w3cValue)) {
            return (int)$w3cValue;
        }
        
        return $w3cValue;
    }
    
    
    /**
     * 
     * @param string $key
     * @return boolean
     */
    private function isContentTypeHeader($key) {
        return strtolower($key) == 'content-type';
       
    }
    
}