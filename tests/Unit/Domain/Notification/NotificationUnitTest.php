<?php

namespace Tests\Unit\Domain\Notification;

use Core\Domain\Notification\Notification;
use PHPUnit\Framework\TestCase;

class NotificationUnitTest extends TestCase
{
    public function testGetErrors()
    {
        $notification = new Notification();
        $errors = $notification->getErrors();

        $this->assertIsArray($errors);
        $this->assertEmpty($errors);
    }

    public function testHasErrors()
    {
        $notification = new Notification();
        $this->assertFalse($notification->hasErrors());

        $notification->addError([
            'context' => 'video',
            'message' => 'video title is required',
        ]);

        $this->assertTrue($notification->hasErrors());
    }

    public function testMessages()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'video title is required',
        ]);

        $this->assertEquals('video: video title is required,', $notification->messages());

        $notification->addError([
            'context' => 'category',
            'message' => 'name is required',
        ]);

        $this->assertEquals('video: video title is required,category: name is required,', $notification->messages());

    }

    public function testMessagesWithContext()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'video title is required',
        ]);

        $notification->addError([
            'context' => 'category',
            'message' => 'name is required',
        ]);

        $this->assertEquals('video: video title is required,', $notification->messages('video'));

    }
}
