<?php

namespace App\Constants;

final class AccountRoles
{
    const OWNER = 'owner';
    const ADMIN = 'admin';

    const ALL = [self::ADMIN, self::OWNER];
    const WITHOUT_OWNER = [self::ADMIN];
}
