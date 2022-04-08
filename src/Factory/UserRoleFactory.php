<?php

namespace App\Factory;

use App\Entity\UserRole;
use App\Repository\UserRoleRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<UserRole>
 *
 * @method static UserRole|Proxy createOne(array $attributes = [])
 * @method static UserRole[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static UserRole|Proxy find(object|array|mixed $criteria)
 * @method static UserRole|Proxy findOrCreate(array $attributes)
 * @method static UserRole|Proxy first(string $sortedField = 'id')
 * @method static UserRole|Proxy last(string $sortedField = 'id')
 * @method static UserRole|Proxy random(array $attributes = [])
 * @method static UserRole|Proxy randomOrCreate(array $attributes = [])
 * @method static UserRole[]|Proxy[] all()
 * @method static UserRole[]|Proxy[] findBy(array $attributes)
 * @method static UserRole[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static UserRole[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static UserRoleRepository|RepositoryProxy repository()
 * @method UserRole|Proxy create(array|callable $attributes = [])
 */
final class UserRoleFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'name' => self::faker()->text(),
            'role' => self::faker()->text(),
            'description' => self::faker()->text()
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(UserRole $userRol): void {})
        ;
    }

    protected static function getClass(): string
    {
        return UserRole::class;
    }
}
