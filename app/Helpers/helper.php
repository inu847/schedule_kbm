<?php

use App\Models\GeneralSetting;

    function generalSetting($name)
    {
        $setting = GeneralSetting::where('name', $name)->first();

        return $setting;
    }
?>