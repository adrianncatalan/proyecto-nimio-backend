<?php

namespace App\Factory;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Company>
 *
 * @method static Company|Proxy createOne(array $attributes = [])
 * @method static Company[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Company|Proxy find(object|array|mixed $criteria)
 * @method static Company|Proxy findOrCreate(array $attributes)
 * @method static Company|Proxy first(string $sortedField = 'id')
 * @method static Company|Proxy last(string $sortedField = 'id')
 * @method static Company|Proxy random(array $attributes = [])
 * @method static Company|Proxy randomOrCreate(array $attributes = [])
 * @method static Company[]|Proxy[] all()
 * @method static Company[]|Proxy[] findBy(array $attributes)
 * @method static Company[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Company[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CompanyRepository|RepositoryProxy repository()
 * @method Company|Proxy create(array|callable $attributes = [])
 */
final class CompanyFactory extends ModelFactory
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
            'name' => self::faker()->company(),
            'taxCode' => self::faker()->unique()->isbn10(),
            'phoneNumber' => self::faker()->e164PhoneNumber()
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Company $company): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Company::class;
    }
}
