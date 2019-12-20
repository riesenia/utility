<?php
/**
 * This file is part of riesenia/utility package.
 *
 * Licensed under the MIT License
 * (c) RIESENIA.com
 */

declare(strict_types=1);

namespace Riesenia\Utility\PHPStan;

use PHPStan\Reflection\ClassMemberReflection;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\FunctionVariant;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\Php\DummyParameter;
use PHPStan\TrinaryLogic;
use PHPStan\Type\Generic\TemplateTypeMap;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

class KendoWidgetMethodReflection implements MethodReflection
{
    /** @var string */
    private $name;

    /** @var ClassReflection */
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

    public function isStatic(): bool
    {
        return false;
    }

    public function isPrivate(): bool
    {
        return false;
    }

    public function isPublic(): bool
    {
        return true;
    }

    public function getDocComment(): ?string
    {
        return null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrototype(): ClassMemberReflection
    {
        return $this;
    }

    /**
     * @return \PHPStan\Reflection\ParametersAcceptor[]
     */
    public function getVariants(): array
    {
        $params = [];

        if (preg_match('/add([A-Z][a-zA-Z0-9]*)/', $this->name)) {
            $params[] = new DummyParameter('key', new MixedType(), false, null, false, null);
        }

        if (preg_match('/(set|add)([A-Z][a-zA-Z0-9]*)/', $this->name)) {
            $params[] = new DummyParameter('value', new MixedType(), false, null, false, null);
        }

        $return = new MixedType();

        if (preg_match('/(set|add)([A-Z][a-zA-Z0-9]*)/', $this->name)) {
            $return = new ObjectType($this->declaringClass->getName());
        }

        return [
            new FunctionVariant(
                TemplateTypeMap::createEmpty(),
                null,
                $params,
                false,
                $return
            )
        ];
    }

    public function isDeprecated(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getDeprecatedDescription(): ?string
    {
        return null;
    }

    public function isFinal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function isInternal(): TrinaryLogic
    {
        return TrinaryLogic::createNo();
    }

    public function getThrowType(): ?Type
    {
        return null;
    }

    public function hasSideEffects(): TrinaryLogic
    {
        return preg_match('/(set|add)([A-Z][a-zA-Z0-9]*)/', $this->name) ? TrinaryLogic::createYes() : TrinaryLogic::createNo();
    }
}
