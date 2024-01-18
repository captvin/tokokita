<?php

class ability
{
    public static function hasAccess($role, $function)
    {
        $roleAbilities = self::getRoleAbilities($role);

        return in_array($function, $roleAbilities);
    }

    private static function getRoleAbilities($role)
    {
        $roles = [
            'kasir' => ['penjualan', 'riwayat_penjualan']
            // Tambahkan fungsi lain yang dapat diakses oleh peran 'kasir'
        ];

        return isset($roles[$role]) ? $roles[$role] : [];
    }
}

?>