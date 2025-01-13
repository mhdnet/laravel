<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('server.updates', function ($user) {
    return  $user instanceof  \App\Models\Admin;
});
