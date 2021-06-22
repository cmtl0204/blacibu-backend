<?php

namespace Database\Seeders;

use App\Models\App\Catalogue;
use App\Models\App\Institution;
use App\Models\App\Professional;
use App\Models\App\Socialmedia;
use App\Models\App\Status;
use App\Models\Authentication\Module;
use App\Models\Authentication\Permission;
use App\Models\Authentication\Role;
use App\Models\Authentication\Route;
use App\Models\Authentication\SecurityQuestion;
use App\Models\Authentication\Shortcut;
use App\Models\Authentication\System;
use App\Models\Authentication\User;
use Illuminate\Database\Seeder;

class AuthenticationSeeder extends Seeder
{
    public function run()
    {
        $this->createStatus();

        // catalogos
        $this->createIdentificationTypeCatalogues();
        $this->createEthnicOriginCatalogues();
        $this->createBloodTypeCatalogues();
        $this->createSexCatalogues();
        $this->createGenderCatalogues();
        $this->createCivilStatusCatalogues();
        $this->createMenus();
        $this->createSectorTypeCatalogues();
        $this->createLanguageCatalogues();
        $this->createLocationCatalogues();
        $this->createSocialmedia();

        $this->createDocumentTypeCatalogues();
        $this->createDocumentCatalogues();
        $this->createConstancyCatalogues();
        $this->createCertificateCatalogues();
        $this->createReCertificateCatalogues();
        $this->createConferenceCatalogues();
        $this->createReConferenceCatalogues();

        // Sistemas
        $this->createSystem();

        // Institutos
//        $this->createInstitutions();

        // Roles para el sistema IGNUG
        $this->createRoles();

        // Modulos
        $this->createModules();

        // Rutas
        $this->createRoutes();

        // Permisos
        $this->createPermissions();


        // Users
        $this->createUsers();

        // Roles con permisos
        $this->createRolePermissions();
        $this->createShortcuts();

        // Users
        $this->createProfessionals();

        // Users con roles
        $this->createUsersRoles();

        // Users con instituciones
//        $this->createUsersInstitutions();

        // Security Questions
        $this->createSecurityQuestions();
    }

    private function createSystem()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $statysAvailable = Status::firstWhere('code', $catalogues['status']['available']);
        System::factory()->create([
            'code' => $catalogues['system']['code'],
            'name' => 'Sistema de Cirugía y Traumatología Bucomaxilofacial',
            'acronym' => 'BLACIBU',
            'version' => '1.2.3',
            'redirect' => 'http://pruebas.blacibu.yavirac.edu.ec/#/',
            'date' => '2021-03-10',
            'status_id' => $statysAvailable->id
        ]);
    }

    private function createStatus()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);

        Status::factory()->create([
            'code' => $catalogues['status']['active'],
            'name' => 'ACTIVO',
        ]);
        Status::factory()->create([
            'code' => $catalogues['status']['inactive'],
            'name' => 'INACTIVO',
        ]);
        Status::factory()->create([
            'code' => $catalogues['status']['locked'],
            'name' => 'BLOQUEADO',
        ]);
        Status::factory()->create([
            'code' => $catalogues['status']['available'],
            'name' => 'HABILITADO',
        ]);
        Status::factory()->create([
            'code' => $catalogues['status']['maintenance'],
            'name' => 'EN MANTENIMIENTO',
        ]);
        Status::factory()->create([
            'code' => $catalogues['status']['accepted'],
            'name' => 'APROBADO',
        ]);
        Status::factory()->create([
            'code' => $catalogues['status']['rejected'],
            'name' => 'RECHAZADO',
        ]);
        Status::factory()->create([
            'code' => $catalogues['status']['in_revision'],
            'name' => 'EN REVISIÓN',
        ]);
    }

    private function createRoles()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $institution = Institution::find(1);
        foreach (System::all() as $system) {
            Role::factory()->create([
                'code' => $catalogues['role']['admin'],
                'name' => 'ADMINISTRADOR',
                'system_id' => $system->id
            ]);

            Role::factory()->create([
                'code' => $catalogues['role']['certified'],
                'name' => 'CERTIFICADO',
                'system_id' => $system->id
            ]);

            Role::factory()->create([
                'code' => $catalogues['role']['recertified'],
                'name' => 'RE-CERTIFICADO',
                'system_id' => $system->id
            ]);
        }
    }

    private function createPermissions()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $system = System::firstWhere('code', $catalogues['system']['code']);
        foreach (Route::all() as $route) {
            Permission::factory()->create([
                'route_id' => $route->id,
                'system_id' => $system->id,
            ]);
        }
    }

    private function createRolePermissions()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $system = System::firstWhere('code', $catalogues['system']['code']);

        foreach (Role::all() as $role) {
            $role->permissions()->attach(Permission::
            where('route_id', 1)
                ->where('system_id', $system->id)
                ->first()
            );
        }

        $roleAdmin = Role::find(1);
        for ($i = 2; $i <= 3; $i++) {
            $roleAdmin->permissions()->attach(Permission::
            where('route_id', $i)
                ->where('system_id', $system->id)
                ->first()
            );
        }

        $roleCerticate = Role::find(2);
        for ($i = 4; $i <= 6; $i++) {
            $roleCerticate->permissions()->attach(Permission::
            where('route_id', $i)
                ->where('system_id', $system->id)
                ->first()
            );
        }

        $roleReCerticate = Role::find(3);
        for ($i = 7; $i <= 9; $i++) {
            $roleReCerticate->permissions()->attach(Permission::
            where('route_id', $i)
                ->where('system_id', $system->id)
                ->first()
            );
        }
    }

    private function createShortcuts()
    {
        Shortcut::create([
            'user_id' => 1,
            'role_id' => 1,
            'permission_id' => 1,
            'name' => 'PERSONAL INFORMATION',
            'image' => 'routes/route4.png',
        ]);

        Shortcut::create([
            'user_id' => 1,
            'role_id' => 1,
            'permission_id' => 2,
            'name' => 'USER ADMINISTRATION',
            'image' => 'routes/route1.png',
        ]);

        Shortcut::create([
            'user_id' => 1,
            'role_id' => 1,
            'permission_id' => 3,
            'name' => 'PROFESSIONAL VALIDATION',
            'image' => 'routes/route2.png',
        ]);
    }

//    private function createInstitutions()
//    {
//        Institution::factory()->create(
//            [
//                'code' => 'bj_1',
//                'name' => 'BENITO JUAREZ',
//                'logo' => 'institutions/1.png',
//                'acronym' => 'BJ',
//                'short_name' => 'BENITO JUAREZ'
//            ]);
//        Institution::factory()->create(
//            [
//                'code' => 'y_2',
//                'name' => 'DE TURISMO Y PATRIMONIO YAVIRAC',
//                'logo' => 'institutions/2.png',
//                'acronym' => 'Y',
//                'short_name' => 'YAVIRAC'
//            ]);
//        Institution::factory()->create(
//            [
//                'code' => '24mayo_3',
//                'name' => '24 DE MAYO',
//                'logo' => 'institutions/3.png',
//                'acronym' => '24MAYO',
//                'short_name' => '24 DE MAYO'
//            ]);
//        Institution::factory()->create(
//            [
//                'code' => 'gc_4',
//                'name' => 'GRAN COLOMBIA',
//                'logo' => 'institutions/4.png',
//                'acronym' => 'GC',
//                'short_name' => 'GRAN COLOMBIA'
//            ]);
//    }

    private function createEthnicOriginCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['ethnic_origin']['indigena'],
            'name' => 'INDIGENA',
            'type' => $catalogues['catalogue']['ethnic_origin']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['ethnic_origin']['afroecuatoriano'],
            'name' => 'AFROECUATORIANO',
            'type' => $catalogues['catalogue']['ethnic_origin']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['ethnic_origin']['negro'],
            'name' => 'NEGRO',
            'type' => $catalogues['catalogue']['ethnic_origin']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['ethnic_origin']['mulato'],
            'name' => 'MULATO',
            'type' => $catalogues['catalogue']['ethnic_origin']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['ethnic_origin']['montuvio'],
            'name' => 'MONTUVIO',
            'type' => $catalogues['catalogue']['ethnic_origin']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['ethnic_origin']['mestizo'],
            'name' => 'MESTIZO',
            'type' => $catalogues['catalogue']['ethnic_origin']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['ethnic_origin']['blanco'],
            'name' => 'BLANCO',
            'type' => $catalogues['catalogue']['ethnic_origin']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['ethnic_origin']['other'],
            'name' => 'OTRO',
            'type' => $catalogues['catalogue']['ethnic_origin']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['ethnic_origin']['unregistered'],
            'name' => 'NO REGISTRA',
            'type' => $catalogues['catalogue']['ethnic_origin']['type'],
        ]);
    }

    private function createIdentificationTypeCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['identification_type']['identification'],
            'name' => 'IDENTIFICACIoN',
            'type' => $catalogues['catalogue']['identification_type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['identification_type']['passport'],
            'name' => 'PASAPORTE',
            'type' => $catalogues['catalogue']['identification_type']['type'],
        ]);
    }

    private function createBloodTypeCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['blood_type']['a-'],
            'name' => 'A-',
            'type' => $catalogues['catalogue']['blood_type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['blood_type']['a+'],
            'name' => 'A+',
            'type' => $catalogues['catalogue']['blood_type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['blood_type']['b-'],
            'name' => 'B-',
            'type' => $catalogues['catalogue']['blood_type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['blood_type']['b+'],
            'name' => 'B+',
            'type' => $catalogues['catalogue']['blood_type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['blood_type']['ab-'],
            'name' => 'AB-',
            'type' => $catalogues['catalogue']['blood_type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['blood_type']['ab+'],
            'name' => 'AB+',
            'type' => $catalogues['catalogue']['blood_type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['blood_type']['o-'],
            'name' => 'O-',
            'type' => $catalogues['catalogue']['blood_type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['blood_type']['o+'],
            'name' => 'O+',
            'type' => $catalogues['catalogue']['blood_type']['type'],
        ]);
    }

    private function createSexCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['sex']['male'],
            'name' => 'HOMBRE',
            'type' => $catalogues['catalogue']['sex']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['sex']['female'],
            'name' => 'MUJER',
            'type' => $catalogues['catalogue']['sex']['type'],
        ]);
    }

    private function createGenderCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['gender']['male'],
            'name' => 'MASCULINO',
            'type' => $catalogues['catalogue']['gender']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['gender']['female'],
            'name' => 'FEMENINO',
            'type' => $catalogues['catalogue']['gender']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['gender']['other'],
            'name' => 'OTRO',
            'type' => $catalogues['catalogue']['gender']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['gender']['not_say'],
            'name' => 'PREFIERO NO DECIRLO',
            'type' => $catalogues['catalogue']['gender']['type'],
        ]);
    }

    private function createCivilStatusCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['civil_status']['married'],
            'name' => 'CASADO/A',
            'type' => $catalogues['catalogue']['civil_status']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['civil_status']['single'],
            'name' => 'SOLTERO/A',
            'type' => $catalogues['catalogue']['civil_status']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['civil_status']['widower'],
            'name' => 'VIUDO/A',
            'type' => $catalogues['catalogue']['civil_status']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['civil_status']['divorced'],
            'name' => 'DIVORCIADO/A',
            'type' => $catalogues['catalogue']['civil_status']['type']
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['civil_status']['union'],
            'name' => 'UNIoN DE HECHO',
            'type' => $catalogues['catalogue']['civil_status']['type']
        ]);
    }

    private function createSectorTypeCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'name' => 'NORTE',
            'type' => $catalogues['catalogue']['sector']['type'],
        ]);
        Catalogue::factory()->create([
            'name' => 'CENTRO',
            'type' => $catalogues['catalogue']['sector']['type'],
        ]);
        Catalogue::factory()->create([
            'name' => 'SUR',
            'type' => $catalogues['catalogue']['sector']['type'],
        ]);
    }

    private function createLanguageCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => 'es',
            'name' => 'ESPAÑOL',
            'type' => $catalogues['catalogue']['language']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => 'br',
            'name' => 'PORTUGUeS',
            'type' => $catalogues['catalogue']['language']['type'],
        ]);
    }

    private function createLocationCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['location']['country'],
            'name' => 'PAiS',
            'type' => $catalogues['catalogue']['location']['type'],
        ]);
    }

    private function createDocumentTypeCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['document_type']['certified'],
            'name' => 'Documentos para certificados',
            'type' => $catalogues['catalogue']['document_type']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['catalogue']['document_type']['recertified'],
            'name' => 'Documentos para re-certificados',
            'type' => $catalogues['catalogue']['document_type']['type'],
        ]);
    }

    private function createDocumentCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $documentTypeCertified = Catalogue::where('code', $catalogues['catalogue']['document_type']['certified'])
            ->where('type', $catalogues['catalogue']['document_type']['type'])->first();

        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '1',
            'name' => 'Titulo universitario de odontologo',
            'type' => $catalogues['catalogue']['document']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '2',
            'name' => 'Cedula, matricula o colegiatura oficial de odontologo',
            'type' => $catalogues['catalogue']['document']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '3',
            'name' => 'Titulo de especialista en cirugia bucomaxilofacial',
            'type' => $catalogues['catalogue']['document']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '4',
            'name' => 'Cedula o matricula oficial en cirugia bucomaxilofacial',
            'type' => $catalogues['catalogue']['document']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '5',
            'name' => 'Cedula o matricula oficial en cirugia bucomaxilofacial',
            'type' => $catalogues['catalogue']['document']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '6',
            'name' => 'Constancia de miembro activo de su sociedad o asociacion',
            'type' => $catalogues['catalogue']['document']['type'],
        ]);
    }

    private function createConstancyCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $documentTypeCertified = Catalogue::where('code', $catalogues['catalogue']['document_type']['certified'])
            ->where('type', $catalogues['catalogue']['document_type']['type'])->first();
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '1',
            'name' => 'Constancia de miembro activo expedida por la Sociedad, Asociacion o Entidad Nacional de su pais',
            'type' => $catalogues['catalogue']['constancy']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '2',
            'name' => 'Curriculum vitae (antecedentes ultimos 6 años)',
            'type' => $catalogues['catalogue']['constancy']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '3',
            'name' => 'Constancia de practica privada exclusiva de la especialidad representada en numero de años',
            'type' => $catalogues['catalogue']['constancy']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '4',
            'name' => 'Distinciones, premios y reconocimientos especiales en la especialidad',
            'type' => $catalogues['catalogue']['constancy']['type'],
        ]);
    }

    private function createCertificateCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $documentTypeCertified = Catalogue::where('code', $catalogues['catalogue']['document_type']['certified'])
            ->where('type', $catalogues['catalogue']['document_type']['type'])->first();
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '1',
            'name' => 'Certificados de asistencia cursos y congresos afines a la especialidad avalados por Alacibu',
            'type' => $catalogues['catalogue']['certificate']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '2',
            'name' => 'Certificados de asistencia cursos y congresos afines a la especialidad no avalados por Alacibu',
            'type' => $catalogues['catalogue']['certificate']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '3',
            'name' => 'Certificados o diplomas de actividades academicas',
            'type' => $catalogues['catalogue']['certificate']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '4',
            'name' => 'Certificados o diplomas de actividades asistenciales',
            'type' => $catalogues['catalogue']['certificate']['type'],
        ]);
    }

    private function createReCertificateCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $documentTypeReCertified = Catalogue::where('code', $catalogues['catalogue']['document_type']['recertified'])
            ->where('type', $catalogues['catalogue']['document_type']['type'])->first();
        Catalogue::factory()->create([
            'parent_id' => $documentTypeReCertified->id,
            'code' => '1',
            'name' => 'Certificados o diplomas de actividades academicas y/o actividades asistenciales',
            'type' => $catalogues['catalogue']['recertificate']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeReCertified->id,
            'code' => '2',
            'name' => 'Trabajos especiales de grado y articulos cientificos publicados',
            'type' => $catalogues['catalogue']['recertificate']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeReCertified->id,
            'code' => '3',
            'name' => 'Actividad como editor o revisor de publicaciones cientificas',
            'type' => $catalogues['catalogue']['recertificate']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeReCertified->id,
            'code' => '4',
            'name' => 'Certificados de asistencia a simposium, cursos o congresos de la especialidad',
            'type' => $catalogues['catalogue']['recertificate']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeReCertified->id,
            'code' => '5',
            'name' => 'Certificados de asistencia a simposio, cursos y congresos no pertenecientes a la especialidad',
            'type' => $catalogues['catalogue']['recertificate']['type'],
        ]);
    }

    private function createConferenceCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $documentTypeCertified = Catalogue::where('code', $catalogues['catalogue']['document_type']['certified'])
            ->where('type', $catalogues['catalogue']['document_type']['type'])->first();
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '1',
            'name' => 'Conferencias presentadas con aval academico de Alacibu',
            'type' => $catalogues['catalogue']['conference']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '2',
            'name' => 'Conferencias presentadas sin aval academico de Alacibu',
            'type' => $catalogues['catalogue']['conference']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '3',
            'name' => 'Trabajos presentados con aval academico de Alacibu',
            'type' => $catalogues['catalogue']['conference']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '4',
            'name' => 'Trabajos presentados sin aval academico de Alacibu',
            'type' => $catalogues['catalogue']['conference']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeCertified->id,
            'code' => '5',
            'name' => 'Trabajos publicados',
            'type' => $catalogues['catalogue']['conference']['type'],
        ]);
    }

    private function createReConferenceCatalogues()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $documentTypeReCertified = Catalogue::where('code', $catalogues['catalogue']['document_type']['recertified'])
            ->where('type', $catalogues['catalogue']['document_type']['type'])->first();
        Catalogue::factory()->create([
            'parent_id' => $documentTypeReCertified->id,
            'code' => '1',
            'name' => 'Conferencias nacionales e internacionales',
            'type' => $catalogues['catalogue']['reconference']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeReCertified->id,
            'code' => '2',
            'name' => 'Conferencias nacionales e internacionales presentadas en CIALACIBU',
            'type' => $catalogues['catalogue']['reconference']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeReCertified->id,
            'code' => '3',
            'name' => 'Afiliacion a asociaciones odontologicas de la especialidad nacionales y en el extranjero',
            'type' => $catalogues['catalogue']['reconference']['type'],
        ]);
        Catalogue::factory()->create([
            'parent_id' => $documentTypeReCertified->id,
            'code' => '4',
            'name' => 'Colaboraciones academicas realizadas para el BLACIBU',
            'type' => $catalogues['catalogue']['reconference']['type'],
        ]);
    }

    private function createMenus()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        Catalogue::factory()->create([
            'code' => $catalogues['menu']['normal'],
            'name' => 'MENU',
            'type' => $catalogues['menu']['type'],
        ]);
        Catalogue::factory()->create([
            'code' => $catalogues['menu']['mega'],
            'name' => 'MEGA MENU',
            'type' => $catalogues['menu']['type'],
        ]);
    }

    private function createModules()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $system = System::firstWhere('code', $catalogues['system']['code']);
        $statusAvailable = Status::firstWhere('code', $catalogues['status']['available']);

        Module::factory()->create([
            'code' => $catalogues['module']['authentication'],
            'name' => 'AUTHENTICATION',
            'icon' => 'pi pi-users',
            'system_id' => $system->id,
            'status_id' => $statusAvailable->id,
        ]);

        Module::factory()->create([
            'code' => $catalogues['module']['app'],
            'name' => 'APP',
            'icon' => 'pi pi-microsoft',
            'system_id' => $system->id,
            'status_id' => $statusAvailable->id,
        ]);
    }

    private function createRoutes()
    {
        $catalogues = json_decode(file_get_contents(storage_path() . "/catalogues.json"), true);
        $moduleAuthentication = Module::firstWhere('code', $catalogues['module']['authentication']);
        $moduleApp = Module::firstWhere('code', $catalogues['module']['app']);
        $menuNormal = Catalogue::firstWhere('code', $catalogues['menu']['normal']);
        $menuMega = Catalogue::firstWhere('code', $catalogues['menu']['mega']);
        $statusAvailable = Status::firstWhere('code', $catalogues['status']['available']);

        Route::factory()->create([
            'uri' => $catalogues['route']['professional']['profile'],
            'module_id' => $moduleApp->id,
            'type_id' => $menuNormal->id,
            'status_id' => $statusAvailable->id,
            'name' => 'PERSONAL INFORMATION',
            'logo' => 'routes/route4.png',
            'order' => 1
        ]);

        // Administrator
        Route::factory()->create([
            'uri' => $catalogues['route']['administrator']['administration'],
            'module_id' => $moduleAuthentication->id,
            'type_id' => $menuNormal->id,
            'status_id' => $statusAvailable->id,
            'name' => 'USERS ADMINISTRATION',
            'logo' => 'routes/route1.png',
            'order' => 2
        ]);
        Route::factory()->create([
            'uri' => $catalogues['route']['administrator']['validation'],
            'module_id' => $moduleAuthentication->id,
            'type_id' => $menuNormal->id,
            'status_id' => $statusAvailable->id,
            'name' => 'DOCUMENTS VALIDATION',
            'logo' => 'routes/route2.png',
            'order' => 3
        ]);


        // Routes Certificate
        Route::factory()->create([
            'uri' => $catalogues['route']['professional']['document'],
            'module_id' => $moduleApp->id,
            'type_id' => $menuNormal->id,
            'status_id' => $statusAvailable->id,
            'name' => 'PROFESSIONALS DOCUEMETNS',
            'logo' => 'routes/route5.png',
            'order' => 3
        ]);
        Route::factory()->create([
            'uri' => $catalogues['route']['professional']['conference'],
            'module_id' => $moduleApp->id,
            'type_id' => $menuNormal->id,
            'status_id' => $statusAvailable->id,
            'name' => 'CONFERENCES AND WORKS',
            'logo' => 'routes/route7.png',
            'order' => 4
        ]);
        Route::factory()->create([
            'uri' => $catalogues['route']['professional']['certificate'],
            'module_id' => $moduleApp->id,
            'type_id' => $menuNormal->id,
            'status_id' => $statusAvailable->id,
            'name' => 'CERTIFICATES',
            'logo' => 'routes/route6.png',
            'order' => 5
        ]);


        // Routes Recertificate
        Route::factory()->create([
            'uri' => $catalogues['route']['professional']['document'],
            'module_id' => $moduleApp->id,
            'type_id' => $menuNormal->id,
            'status_id' => $statusAvailable->id,
            'name' => 'CONSTANCES',
            'logo' => 'routes/route9.png',
            'order' => 2
        ]);
        Route::factory()->create([
            'uri' => $catalogues['route']['professional']['recertificate'],
            'module_id' => $moduleApp->id,
            'type_id' => $menuNormal->id,
            'status_id' => $statusAvailable->id,
            'name' => 'CERTIFICATES',
            'logo' => 'routes/route10.png',
            'order' => 3
        ]);
        Route::factory()->create([
            'uri' => $catalogues['route']['professional']['reconference'],
            'module_id' => $moduleApp->id,
            'type_id' => $menuNormal->id,
            'status_id' => $statusAvailable->id,
            'name' => 'CONFERENCES AND AFFILIATIONS',
            'logo' => 'routes/route11.png',
            'order' => 4
        ]);
    }

    private function createUsers()
    {
        User::factory()->create([
            'username' => '1234567890',
            'identification' => '1234567890',
        ]);
        User::factory()->count(10)->create();
    }

    private function createProfessionals()
    {
        foreach (User::all() as $user) {
            Professional::factory()->create([
                'user_id' => $user->id,
                'status_id' => 8,
            ]);
        }
    }

    private function createUsersRoles()
    {
        $user = User::find(1);

//        foreach (Role::all() as $role) {
        $user->roles()->attach(1);
//        }
//        $user = User::where('id', '!=', 1)->get();

//        foreach ($user as $users) {
//            $users->roles()->attach(random_int(1, Role::all()->count()));
//        }
    }

    private function createUsersInstitutions()
    {
        $user = User::find(1);

        foreach (Institution::all() as $institution) {
            $user->institutions()->syncWithoutDetaching($institution->id);
        }
    }

    private function createSecurityQuestions()
    {
        SecurityQuestion::factory()->create(['name' => '¿Cual es el nombre de su padre?']);
        SecurityQuestion::factory()->create(['name' => '¿Cual es el nombre de su madre?']);
        SecurityQuestion::factory()->create(['name' => '¿Cual es el nombre de su mascota?']);
        SecurityQuestion::factory()->create(['name' => '¿Cual es el nombre de su mejor amigo de la infancia?']);
        SecurityQuestion::factory()->create(['name' => '¿Cual es el nombre de su color favorito?']);
        SecurityQuestion::factory()->create(['name' => '¿Cual es el nombre de su fruta favorita?']);
        SecurityQuestion::factory()->create(['name' => '¿Cual es el nombre de su abuela materna?']);
        SecurityQuestion::factory()->create(['name' => '¿Cual es el nombre de su abuela paterna?']);
        SecurityQuestion::factory()->create(['name' => '¿Cual es su marca de auto favorito?']);
        SecurityQuestion::factory()->create(['name' => '¿Cual es el nombre de su cancion favorita?']);
    }

    private function createSocialmedia()
    {
        Socialmedia::create([
            'name' => 'FACEBOOK',
            'icon' => 'pi pi-facebook',
            'logo' => 'socialmedia/facebook.png',
        ]);
        Socialmedia::create([
            'name' => 'INSTAGRAM',
            'icon' => 'pi pi-camera',
            'logo' => 'socialmedia/instagram.png',
        ]);
        Socialmedia::create([
            'name' => 'LINKEDIN',
            'icon' => 'pi pi-id-card',
            'logo' => 'socialmedia/linkedin.png',
        ]);
        Socialmedia::create([
            'name' => 'TWITTER',
            'icon' => 'pi pi-twitter',
            'logo' => 'socialmedia/twitter.png',
        ]);
        Socialmedia::create([
            'name' => 'YOUTUBE',
            'icon' => 'pi pi-youtube',
            'logo' => 'socialmedia/youtube.png',
        ]);
    }
}
