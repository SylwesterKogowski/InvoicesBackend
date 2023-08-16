<?php

declare(strict_types=1);

namespace Tests\Helpers;

use PHPUnit\Framework\MockObject\Invocation;
use PHPUnit\Framework\MockObject\Stub\Stub;

/**
 * Combines multiple invocation stubs from which the first non-null result is returned
 * Usage:
 * $mock->method('foo')->will(new CombinedInvocationStub($stub1, $stub2, new ReturnStub($return_value)));
 */
class CombinedInvocationStub implements Stub
{
    /**
     * @var Stub[]
     */
    private array $stubs;

    public function __construct(Stub ...$stubs)
    {
        $this->stubs = $stubs;
    }

    /**
     * @inheritDoc
     */
    public function toString(): string
    {
        return "Combined " . implode(" and ", array_map(fn($s) => $s->toString(), $this->stubs));
    }

    /**
     * @inheritDoc
     */
    public function invoke(Invocation $invocation)
    {
        $result = null;
        foreach ($this->stubs as $stub) {
            $_result = $stub->invoke($invocation);
            if ($_result) {
                $result = $_result;
            }
        }
        return $result;
    }
}
