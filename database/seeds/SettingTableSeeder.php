<?php

use App\Models\SettingGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        try {
            $settings = [
                [
                    ['name' => 'app_name', 'value' => 'Codec7', 'default_value' => 'Codec7', 'type' => 'text', 'comment' => null, 'required' => 1, 'order' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                    ['name' => 'app_description', 'value' => 'CoffeeDev Core 7', 'default_value' => 'CoffeeDev Core 7', 'type' => 'textarea', 'comment' => null, 'required' => 1, 'order' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                    ['name' => 'app_logo', 'value' => null, 'default_value' => null, 'type' => 'file', 'comment' => null, 'required' => 0, 'order' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                    ['name' => 'favicon', 'value' => null, 'default_value' => null, 'type' => 'file', 'comment' => null, 'required' => 0, 'order' => 4, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                    ['name' => 'app_author', 'value' => 'Rio Prastiawan', 'default_value' => 'Rio Prastiawan', 'type' => 'text', 'comment' => null, 'required' => 1, 'order' => 5, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                    ['name' => 'app_version', 'value' => '1.0.0', 'default_value' => '1.0.0', 'type' => 'text', 'comment' => null, 'required' => 1, 'order' => 6, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ],
                [
                    ['name' => 'email_from_noreply', 'value' => 'no-reply@codec7.test', 'default_value' => 'no-reply@codec7.test', 'type' => 'email', 'comment' => null, 'required' => 1, 'order' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                    ['name' => 'email_subject_activation', 'value' => 'Aktifasi Email', 'default_value' => 'Aktifasi Email', 'type' => 'text', 'comment' => null, 'required' => 1, 'order' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                    ['name' => 'email_subject_forgot_password', 'value' => 'Lupa Kata Sandi', 'default_value' => 'Lupa Kata Sandi', 'type' => 'text', 'comment' => null, 'required' => 1, 'order' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                    ['name' => 'email_subject_new_device_login', 'value' => 'Notifikasi Keamanan', 'default_value' => 'Notifikasi Keamanan', 'type' => 'text', 'comment' => null, 'required' => 1, 'order' => 2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ]
            ];

            foreach ($settings as $key => $setting) {
                $key++;
                $settingGroup = SettingGroup::find(['id' => $key])->first();

                if (!$settingGroup) {
                    continue;
                }

                foreach($setting as $record){
                    $settingGroup->settings()->create($record);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
        }
    }
}
