<?php

namespace App\Middleware;

use App\Models\MessagePriveeModel;

class MessagePriveeMiddleware
{
    public function handle()
    {
        if (isset($_SESSION['id'])) {
            $id_utilisateur = $_SESSION['id'];
            $messagePriveeModel = new MessagePriveeModel();
            $_SESSION['nouveauMessage'] = $messagePriveeModel->nouveauMessage($id_utilisateur);
        }
    }
}