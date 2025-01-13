<?php

namespace App\Constants;

final class RolesName
{
    const ADMIN ='admin';
    const SUPER = 'super-admin';

    const DELEGATE ='delegate';

    const CLIENT ='client';

    const SUPER_ADMIN =[RolesName::SUPER, RolesName::ADMIN];

    const ALL  = [RolesName::SUPER, RolesName::ADMIN, RolesName::DELEGATE, RolesName::CLIENT, ];
}
