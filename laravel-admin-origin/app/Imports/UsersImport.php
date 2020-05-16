<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
     *   'name', 'mobile', 'password','type_id','job','description'
    */
    public function model(array $row)
    {
        //如果需要去除表头

        return new User([
            'name'     => $row[0],
            'mobile'   => $row[1],
            'password' =>  substr(md5($row[2]),3,20),
            'type_id' =>  $row[3],
            'job' =>  $row[4],
            'description' =>  $row[5]
        ]);
    }
}
