<?php
declare(strict_types=1);
namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserInterface
{
    public function registerHandler(Request $request);

    public function loginHandler(Request $request);

    public function logout();

    public function refresh();
}