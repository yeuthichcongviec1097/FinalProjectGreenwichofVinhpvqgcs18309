<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class AdminEditUserComponent extends Component
{
    public $user_id;
    public $name;
    public $email;
    public $utype;
    public $confirmChangePassword;
    public $password;

    public function mount($id){
        $this->user_id = $id;
        $user = User::where('id', $id)->first();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->utype = $user->utype;
        $this->password = $user->password;
    }

    public function updateUser(){
        $user = User::find($this->user_id);
        $user->name = $this->name;
        $user->utype = $this->utype;
        $user->password = $this->password;
        $user->save();
        session()->flash('message', 'User has been updated successfully !!!');
        $this->emit('alert', ['type' => 'success', 'message' => 'User has been updated successfully !!!']);
    }

    public function render()
    {
        return view('livewire.admin.admin-edit-user-component')->layout('layouts.base');
    }
}
