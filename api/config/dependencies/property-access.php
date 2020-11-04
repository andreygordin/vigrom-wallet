<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

return [
    PropertyAccessorInterface::class => function (): PropertyAccessorInterface {
        return PropertyAccess::createPropertyAccessorBuilder()
            ->enableExceptionOnInvalidPropertyPath()
            ->enableExceptionOnInvalidIndex()
            ->getPropertyAccessor();
    },
];
