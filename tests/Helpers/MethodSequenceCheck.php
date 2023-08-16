<?php

declare(strict_types=1);

namespace Tests\Helpers;

use PHPUnit\Framework\MockObject\Invocation;
use PHPUnit\Framework\MockObject\Stub\Stub;

/**
 * Allows for checking the sequence in which methods were called
 * Works with different objects, unlike the phpunit version
 * usage
 * $sequenceChecker = new MethodSequenceCheck();
 * $mock->method('foo')->will($sequenceChecker);
 * $another_mock->method('foo2')->will($sequenceChecker);
 * $mock->foo();
 * $another_mock->foo2();
 * $this->assertEquals(['foo','foo2'], $sequenceChecker->getSequence());
 */
class MethodSequenceCheck implements Stub
{
    /**
     * @var string[]
     */
    private $sequence = [];
    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return "";
    }

    /**
     * @inheritDoc
     */
    public function invoke(Invocation $invocation)
    {
        array_push($this->sequence, $invocation->getMethodName());
    }

    /**
     * @return string[] sequence of method names called
     */
    public function getSequence(): array
    {
        return $this->sequence;
    }
}
