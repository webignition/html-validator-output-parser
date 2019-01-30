<?php

namespace webignition\HtmlValidatorOutput\Parser;

class InvalidContentTypeException extends \Exception
{
    const MESSAGE = 'Invalid content type: %s';
    const CODE = 1;

    public function __construct(string $contentType)
    {
        parent::__construct(
            sprintf(self::MESSAGE, $contentType),
            self::CODE
        );
    }
}
