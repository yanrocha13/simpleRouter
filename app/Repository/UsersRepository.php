<?php
declare(strict_types=1);

namespace Demo\Repository;

use Demo\Models\Users;
use Illuminate\Database\Eloquent\Model;

class UsersRepository
{
    public function whereFirst($parameter, $data){
        return $users = Users::where($parameter,$data)->first();
    }

    public function create($user){
        return Users::create($user);
    }

    public function list(){
        return Users::all();
    }

    public function find($id){
        return Users::find($id);
    }

    public function update($id, $update){
        return Users::where('id',$id)
            ->update($update);
    }
}
