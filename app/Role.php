<?php

namespace App;

use Zizaco\Entrust\EntrustRole;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Role extends EntrustRole
{
    use BelongsToTenants;
}
