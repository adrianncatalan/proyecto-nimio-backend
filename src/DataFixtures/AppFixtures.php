<?php

namespace App\DataFixtures;

use App\Factory\ProjectFactory;
use App\Factory\UserFactory;
use App\Factory\UserRoleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\CompanyFactory;
use App\Factory\CustomerFactory;
use App\Factory\TaskFactory;
use function Zenstruck\Foundry\faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $dateCreate = new \datetime('05-04-2022');

        //CREACION DE ROLES MANUALES
        $superAdmin = UserRoleFactory::new()->create([
            'name' => 'SuperAdmin',
            'role' => 'ROLE_SUPERADMIN',
            'description' => 'Este rol permite administrar todas las empresas',
        ]);
        $admin = UserRoleFactory::new()->create([
            'name' => 'Administrador',
            'role' => 'ROLE_ADMIN',
            'description' => 'Este rol puede modificar administrar una empresa',
        ]);
        $trabajador = UserRoleFactory::new()->create([
            'name' => 'Trabajador',
            'role' => 'ROLE_WORKER',
            'description' => 'Este rol es un usuario de una empresa',
        ]);

        //CREACION DE EMPRESA MANUAL
        $empresa = CompanyFactory::new()->create([
            'name' => 'Nimio Estudio',
        ]);

        //CREACION DE USUARIOS MANUALES
        UserFactory::new()->create([
            'name' => 'Pep',
            'surnames' => 'Guardiola Lopez',
            'email' => 'pep@gmail.com',
            'emailVerify' => true,
            'userRoles' => [$superAdmin]
        ]);
        UserFactory::new()->create([
            'name' => 'Maria',
            'surnames' => 'Soto Lopez',
            'email' => 'maria@gmail.com',
            'emailVerify' => true,
            'userRoles' => [$admin],
            'company' => $empresa
        ]);
        UserFactory::new()->create([
            'name' => 'Javi',
            'surnames' => 'Molina Perez',
            'email' => 'javi@gmail.com',
            'emailVerify' => true,
            'userRoles' => [$trabajador],
            'company' => $empresa
        ]);


        CompanyFactory::createMany(20);
        CustomerFactory::createMany(10, function () {
            return[
                'company' => CustomerFactory::faker()->boolean(80) ? CompanyFactory::random() : null
            ];
        });
        //CREACION DE USUARIOS Y TABLA N:M
        UserFactory::createMany(10, function()  use ($admin, $trabajador) {
            if (UserFactory::faker()->boolean(10)) {
                $item['userRoles'] = [$admin];
            }else{
                $item['userRoles'] = [$trabajador];
            }
            $item['company'] = CompanyFactory::random();
            return $item;
        });

        //CREACION DE PROYECTOS
        ProjectFactory::createMany(20, function () {
            return[
                'customer' => CustomerFactory::random()
            ];
        });

        //CREACION DE TAREAS
        TaskFactory::createMany(40, function () {
            return[
                'project' => ProjectFactory::random(),
                'user' => UserFactory::random()
            ];
        });

        $manager->flush();
    }
}
