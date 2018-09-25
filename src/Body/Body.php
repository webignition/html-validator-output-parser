<?php

namespace webignition\HtmlValidator\Output\Body;

class Body
{
    /**
     * @var \stdClass
     */
    private $content = null;

    public function setContent(\stdClass $content): Body
    {
        $this->content = $content;

        return $this;
    }

    public function hasContent(): bool
    {
        return !is_null($this->content);
    }

    public function getContent(): \stdClass
    {
        return $this->content;
    }

    public function getMessages(): array
    {
        if (!$this->hasContent()) {
            return [];
        }

        return $this->getContent()->messages;
    }
}
