<?php

/**
 * @author Andrey Gordin <andrey@gordin.su>
 */

declare(strict_types=1);

use App\Validator\ConstraintValidatorFactory;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

return [
    ValidatorInterface::class => function (ContainerInterface $container): ValidatorInterface {
        /** @psalm-suppress DeprecatedMethod */
        AnnotationRegistry::registerLoader('class_exists');

        /** @var ConstraintValidatorFactory $validatorFactory */
        $validatorFactory = $container->get(ConstraintValidatorFactory::class);

        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->setConstraintValidatorFactory($validatorFactory)
            ->getValidator();
    },

    ConstraintValidatorFactory::class => function (ContainerInterface $container): ConstraintValidatorFactory {
        return new ConstraintValidatorFactory(
            function (string $className) use ($container): ConstraintValidatorInterface {
                /** @var ConstraintValidatorInterface $validator */
                $validator = $container->get($className);
                return $validator;
            }
        );
    },
];
