<?php

namespace webignition\Tests\HtmlValidator\Body;

use webignition\HtmlValidator\Output\Body\Body;

class BodyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMessagesWithNoContent()
    {
        $body = new Body();
        $this->assertEquals([], $body->getMessages());
    }
}
