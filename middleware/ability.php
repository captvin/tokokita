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
            'kasir' => ['createJual', 'createBeli', 'getBarang', 'getSupplier', 'getPelanggan', 'getUserById'],
            'admin' => ['createJual', 'createBeli', 'createUser', 'createBarang', 'createSupplier', 'createPelanggan', 'getJual', 'getBeli', 'getUser', 'getBarang', 'getSupplier', 'getPelanggan', 'patchJual', 'patchBeli', 'patchUser', 'patchBarang', 'patchSupplier', 'patchPelanggan', 'deleteJual', 'deleteBeli', 'deleteUser', 'deleteBarang', 'deleteSupplier', 'deletePelanggan', 'getSummary', 'getUserById'],
            'manager' => ['getSummary', 'getUserById']
            // Tambahkan fungsi lain yang dapat diakses oleh peran 'kasir'
        ];

        return isset($roles[$role]) ? $roles[$role] : [];
    }
}

?>