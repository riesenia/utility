<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\PHPStan;

use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Riesenia\Kendo\Kendo;

class KendoMethodReflection implements MethodReflection
{
    /** @var string */
    private $name;

    /** @var \PHPStan\Reflection\ClassReflection */
    private $declaringClass;

    public function __construct(string $name, ClassReflection $declaringClass)
    {
        $this->name = $name;
        $this->declaringClass = $declaringClass;
    }

    public function getDeclaringClass(): ClassReflection
    {
        return $this->declaringClass;
    }

    public function getPrototype(): MethodReflection
    {
        return $this;
    }

    public function isStatic(): bool
    {
        return true;
    }

    /**
     * @return \PHPStan\Reflection\ParameterReflection[]
     */
    public function getParameters(): array
    {
        return [];
    }

    public function isVariadic(): bool
    {
        return true;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReturnType(): Type
    {
        $obj = Kendo::{$this->name}();

        return new ObjectType(get_class($obj));
    }
}
