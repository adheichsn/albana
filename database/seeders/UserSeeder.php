<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'=>'Owner',
                'email'=>'owner@owner.com',
                'password'=> Hash::make('owner')
            ],
            [
                'name'=>'Admin',
                'email'=>'admin@admin.com',
                'password'=> Hash::make('admin')
            ],
            [
                'name'=>'imam',
                'email'=>'syivahidayat@admin.com',
                'password'=> Hash::make('imam1234')
            ]
        ];



        foreach($users as $user){
            User::create($user);
        }
    }
}
