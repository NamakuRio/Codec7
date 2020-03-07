<?php

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try{
            $roles = [
                ['name' => 'developer', 'default_user' => 0, 'login_destination' => 'admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ];

            Role::insert($roles);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
