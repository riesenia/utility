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
use PHPStan\Reflection\MethodsClassReflectionExtension;
use Riesenia\Kendo\Widget\Base;

class KendoWidgetClassReflectionExtension implements MethodsClassReflectionExtension
{
    public function hasMethod(ClassReflection $classReflection, string $methodName): bool
    {
        if ($classReflection->getName() === Base::class || $classReflection->isSubclassOf(Base::class)) {
            return (bool) preg_match('/(set|add|get)([A-Z][a-zA-Z0-9]*)/', $methodName);
        }

        return false;
    }

    public function getMethod(ClassReflection $classReflection, string $methodName): MethodReflection
    {
        return new KendoWidgetMethodReflection($methodName, $classReflection);
    }
}
