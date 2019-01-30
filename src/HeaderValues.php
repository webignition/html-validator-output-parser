<?php

namespace webignition\HtmlValidatorOutput\Parser;

use webignition\InternetMediaTypeInterface\InternetMediaTypeInterface;

class HeaderValues
{
    private $contentType;
    private $wasAborted;

    public function __construct(bool $wasAborted, InternetMediaTypeInterface $contentType)
    {
        $this->contentType = $contentType;
        $this->wasAborted = $wasAborted;
    }

    public function getContentType(): InternetMediaTypeInterface
    {
        return $this->contentType;
    }

    public function getWasAborted(): bool
    {
        return $this->wasAborted;
    }
}
