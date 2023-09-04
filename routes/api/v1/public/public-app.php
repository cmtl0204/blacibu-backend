<?php

use App\Http\Controllers\App\CatalogueController;
use App\Http\Controllers\App\LocationController;
use App\Http\Requests\Authentication\Auth\CreateClientRequest;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


Route::apiResource('catalogues', CatalogueController::class);

Route::apiResource('locations', LocationController::class);

Route::group(['prefix' => 'location'], function () {
    Route::get('get', [LocationController::class, 'getLocations']);
    Route::get('countries', [LocationController::class, 'getCountries']);
});

Route::get('init', function (CreateClientRequest $request) {

    if (env('APP_ENV') != 'local') {
        return 'El sistema se encuentra en producción.';
    }

    DB::select('drop schema if exists public cascade;');
    DB::select('drop schema if exists authentication cascade;');
    DB::select('drop schema if exists app cascade;');

    DB::select('create schema authentication;');
    DB::select('create schema app;');

    Artisan::call('migrate', ['--seed' => true]);

    Artisan::call('passport:client', [
        '--password' => true,
        '--name' => 'Password-' . $request->input('client_name'),
        '--quiet' => true,
    ]);

    Artisan::call('passport:client', [
        '--personal' => true,
        '--name' => 'Client-' . $request->input('client_name'),
        '--quiet' => true,
    ]);

    $clientSecret = DB::select("select secret from oauth_clients where name='" . 'Password-' . $request->input('client_name') . "'");

    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'1','AFGHANISTAN','','','af')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'2','ÅLAND ISLANDS','','','ax')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'3','ALBANIA','','','al')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'4','ALGERIA','','','dz')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'5','AMERICAN SAMOA','','','as')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'6','ANDORRA','','','ad')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'7','ANGOLA','','','ao')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'8','ANGUILLA','','','ai')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'9','ANTARCTICA','','','aq')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'10','ANTIGUA AND BARBUDA','','','ag')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'11','ARGENTINA','AMERICA','LATIN_AMERICA','ar')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'12','ARMENIA','','','am')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'13','ARUBA','','','aw')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'14','AUSTRALIA','','','au')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'15','AUSTRIA','','','at')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'16','AZERBAIJAN','','','az')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'17','BAHAMAS','','','bs')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'18','BAHRAIN','','','bh')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'19','BANGLADESH','','','bd')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'20','BARBADOS','','','bb')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'21','BELARUS','','','by')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'22','BELGIUM','','','be')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'23','BELIZE','','','bz')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'24','BENIN','','','bj')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'25','BERMUDA','','','bm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'26','BHUTAN','','','bt')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'27','BOLIVIA','AMERICA','LATIN_AMERICA','bo')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'28','BONAIRE, SINT EUSTATIUS AND SABA','','','bq')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'29','BOSNIA AND HERZEGOVINA','','','ba')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'30','BOTSWANA','','','bw')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'31','BOUVET ISLAND','','','bv')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'32','BRAZIL','AMERICA','LATIN_AMERICA','br')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'33','BRITISH INDIAN OCEAN TERRITORY','','','io')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'34','UNITED STATES MINOR OUTLYING ISLANDS','','','um')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'35','VIRGIN ISLANDS (BRITISH)','','','vg')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'36','VIRGIN ISLANDS (U.S.)','','','vi')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'37','BRUNEI DARUSSALAM','','','bn')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'38','BULGARIA','','','bg')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'39','BURKINA FASO','','','bf')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'40','BURUNDI','','','bi')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'41','CAMBODIA','','','kh')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'42','CAMEROON','','','cm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'43','CANADA','','','ca')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'44','CABO VERDE','','','cv')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'45','CAYMAN ISLANDS','','','ky')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'46','CENTRAL AFRICAN REPUBLIC','','','cf')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'47','CHAD','','','td')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'48','CHILE','AMERICA','LATIN_AMERICA','cl')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'49','CHINA','','','cn')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'50','CHRISTMAS ISLAND','','','cx')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'51','COCOS (KEELING) ISLANDS','','','cc')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'52','COLOMBIA','AMERICA','LATIN_AMERICA','co')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'53','COMOROS','','','km')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'54','CONGO','','','cg')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'55','CONGO (DEMOCRATIC REPUBLIC OF THE)','','','cd')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'56','COOK ISLANDS','','','ck')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'57','COSTA RICA','','','cr')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'58','CROATIA','','','hr')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'59','CUBA','AMERICA','LATIN_AMERICA','cu')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'60','CURAÇAO','','','cw')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'61','CYPRUS','','','cy')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'62','CZECH REPUBLIC','','','cz')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'63','DENMARK','','','dk')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'64','DJIBOUTI','','','dj')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'65','DOMINICA','','','dm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'66','DOMINICAN REPUBLIC','','','do')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'67','ECUADOR','AMERICA','LATIN_AMERICA','ec')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'68','EGYPT','','','eg')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'69','EL SALVADOR','AMERICA','LATIN_AMERICA','sv')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'70','EQUATORIAL GUINEA','','','gq')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'71','ERITREA','','','er')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'72','ESTONIA','','','ee')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'73','ETHIOPIA','','','et')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'74','FALKLAND ISLANDS (MALVINAS)','','','fk')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'75','FAROE ISLANDS','','','fo')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'76','FIJI','','','fj')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'77','FINLAND','','','fi')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'78','FRANCE','','','fr')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'79','FRENCH GUIANA','','','gf')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'80','FRENCH POLYNESIA','','','pf')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'81','FRENCH SOUTHERN TERRITORIES','','','tf')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'82','GABON','','','ga')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'83','GAMBIA','','','gm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'84','GEORGIA','','','ge')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'85','GERMANY','','','de')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'86','GHANA','','','gh')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'87','GIBRALTAR','','','gi')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'88','GREECE','','','gr')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'89','GREENLAND','','','gl')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'90','GRENADA','','','gd')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'91','GUADELOUPE','','','gp')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'92','GUAM','','','gu')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'93','GUATEMALA','AMERICA','LATIN_AMERICA','gt')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'94','GUERNSEY','','','gg')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'95','GUINEA','','','gn')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'96','GUINEA-BISSAU','','','gw')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'97','GUYANA','','','gy')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'98','HAITI','AMERICA','LATIN_AMERICA','ht')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'99','HEARD ISLAND AND MCDONALD ISLANDS','','','hm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'100','HOLY SEE','','','va')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'101','HONDURAS','AMERICA','LATIN_AMERICA','hn')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'102','HONG KONG','','','hk')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'103','HUNGARY','','','hu')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'104','ICELAND','','','is')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'105','INDIA','','','in')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'106','INDONESIA','','','id')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'107','CÔTE DIVOIRE','','','ci')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'108','IRAN (ISLAMIC REPUBLIC OF)','','','ir')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'109','IRAQ','','','iq')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'110','IRELAND','','','ie')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'111','ISLE OF MAN','','','im')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'112','ISRAEL','','','il')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'113','ITALY','','','it')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'114','JAMAICA','','','jm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'115','JAPAN','','','jp')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'116','JERSEY','','','je')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'117','JORDAN','','','jo')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'118','KAZAKHSTAN','','','kz')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'119','KENYA','','','ke')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'120','KIRIBATI','','','ki')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'121','KUWAIT','','','kw')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'122','KYRGYZSTAN','','','kg')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'123','LAO PEOPLES DEMOCRATIC REPUBLIC','','','la')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'124','LATVIA','','','lv')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'125','LEBANON','','','lb')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'126','LESOTHO','','','ls')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'127','LIBERIA','','','lr')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'128','LIBYA','','','ly')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'129','LIECHTENSTEIN','','','li')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'130','LITHUANIA','','','lt')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'131','LUXEMBOURG','','','lu')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'132','MACAO','','','mo')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'133','MACEDONIA (THE FORMER YUGOSLAV REPUBLIC OF)','','','mk')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'134','MADAGASCAR','','','mg')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'135','MALAWI','','','mw')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'136','MALAYSIA','','','my')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'137','MALDIVES','','','mv')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'138','MALI','','','ml')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'139','MALTA','','','mt')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'140','MARSHALL ISLANDS','','','mh')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'141','MARTINIQUE','','','mq')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'142','MAURITANIA','','','mr')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'143','MAURITIUS','','','mu')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'144','MAYOTTE','','','yt')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'145','MEXICO','AMERICA','LATIN_AMERICA','mx')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'146','MICRONESIA','','','fm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'147','MOLDOVA ','','','md')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'148','MONACO','','','mc')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'149','MONGOLIA','','','mn')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'150','MONTENEGRO','','','me')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'151','MONTSERRAT','','','ms')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'152','MOROCCO','','','ma')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'153','MOZAMBIQUE','','','mz')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'154','MYANMAR','','','mm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'155','NAMIBIA','','','na')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'156','NAURU','','','nr')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'157','NEPAL','','','np')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'158','NETHERLANDS','','','nl')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'159','NEW CALEDONIA','','','nc')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'160','NEW ZEALAND','','','nz')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'161','NICARAGUA','AMERICA','LATIN_AMERICA','ni')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'162','NIGER','','','ne')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'163','NIGERIA','','','ng')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'164','NIUE','','','nu')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'165','NORFOLK ISLAND','','','nf')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'166','KOREA','','','kp')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'167','NORTHERN MARIANA ISLANDS','','','mp')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'168','NORWAY','','','no')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'169','OMAN','','','om')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'170','PAKISTAN','','','pk')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'171','PALAU','','','pw')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'172','PALESTINE, STATE OF','','','ps')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'173','PANAMA','AMERICA','LATIN_AMERICA','pa')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'174','PAPUA NEW GUINEA','','','pg')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'175','PARAGUAY','AMERICA','LATIN_AMERICA','py')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'176','PERU','AMERICA','LATIN_AMERICA','pe')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'177','PHILIPPINES','','','ph')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'178','PITCAIRN','','','pn')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'179','POLAND','','','pl')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'180','PORTUGAL','','','pt')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'181','PUERTO RICO','','','pr')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'182','QATAR','','','qa')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'183','REPUBLIC OF KOSOVO','','','xk')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'184','RÉUNION','','','re')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'185','ROMANIA','','','ro')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'186','RUSSIAN FEDERATION','','','ru')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'187','RWANDA','','','rw')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'188','SAINT BARTHÉLEMY','','','bl')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'189','SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA','','','sh')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'190','SAINT KITTS AND NEVIS','','','kn')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'191','SAINT LUCIA','','','lc')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'192','SAINT MARTIN (FRENCH PART)','','','mf')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'193','SAINT PIERRE AND MIQUELON','','','pm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'194','SAINT VINCENT AND THE GRENADINES','','','vc')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'195','SAMOA','','','ws')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'196','SAN MARINO','','','sm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'197','SAO TOME AND PRINCIPE','','','st')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'198','SAUDI ARABIA','','','sa')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'199','SENEGAL','','','sn')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'200','SERBIA','','','rs')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'201','SEYCHELLES','','','sc')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'202','SIERRA LEONE','','','sl')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'203','SINGAPORE','','','sg')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'204','SINT MAARTEN (DUTCH PART)','','','sx')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'205','SLOVAKIA','','','sk')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'206','SLOVENIA','','','si')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'207','SOLOMON ISLANDS','','','sb')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'208','SOMALIA','','','so')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'209','SOUTH AFRICA','','','za')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'210','SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS','','','gs')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'211','KOREA (REPUBLIC OF)','','','kr')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'212','SOUTH SUDAN','','','ss')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'213','SPAIN','','','es')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'214','SRI LANKA','','','lk')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'215','SUDAN','','','sd')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'216','SURINAME','','','sr')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'217','SVALBARD AND JAN MAYEN','','','sj')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'218','SWAZILAND','','','sz')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'219','SWEDEN','','','se')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'220','SWITZERLAND','','','ch')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'221','SYRIAN ARAB REPUBLIC','','','sy')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'222','TAIWAN','','','tw')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'223','TAJIKISTAN','','','tj')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'224','TANZANIA, UNITED REPUBLIC OF','','','tz')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'225','THAILAND','','','th')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'226','TIMOR-LESTE','','','tl')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'227','TOGO','','','tg')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'228','TOKELAU','','','tk')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'229','TONGA','','','to')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'230','TRINIDAD AND TOBAGO','','','tt')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'231','TUNISIA','','','tn')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'232','TURKEY','','','tr')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'233','TURKMENISTAN','','','tm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'234','TURKS AND CAICOS ISLANDS','','','tc')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'235','TUVALU','','','tv')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'236','UGANDA','','','ug')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'237','UKRAINE','','','ua')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'238','UNITED ARAB EMIRATES','','','ae')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'239','UNITED KINGDOM OF GREAT BRITAIN AND NORTHERN IRELAND','','','gb')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'240','UNITED STATES OF AMERICA','','','us')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'241','URUGUAY','AMERICA','LATIN_AMERICA','uy')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'242','UZBEKISTAN','','','uz')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'243','VANUATU','','','vu')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'244','VENEZUELA','AMERICA','LATIN_AMERICA','ve')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'245','VIET NAM','','','vn')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'246','WALLIS AND FUTUNA','','','wf')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'247','WESTERN SAHARA','','','eh')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'248','YEMEN','','','ye')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'249','ZAMBIA','','','zm')");
    DB::select("insert into app.locations(type_id,code,name,region,subregion,flag) values(38,'250','ZIMBABWE','','','zw')");

    return response()->json([
        'msg' => [
            'Los esquemas fueron creados correctamente.',
            'Las migraciones fueron creadas correctamente',
            'Cliente para la aplicación creado correctamente',
        ],
        'client' => $clientSecret[0]->secret
    ]);
});
