<?php

namespace webignition\HtmlValidator\Output\Body;

class Body
{
    /**
     * @var \stdClass
     */
    private $content = null;

    /**
     * @param \stdClass $content
     *
     * @return Body
     */
    public function setContent(\stdClass $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasContent()
    {
        return !is_null($this->content);
    }

    /**
     * @return \stdClass
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        if (!$this->hasContent()) {
            return [];
        }

        return $this->getContent()->messages;
    }
}
