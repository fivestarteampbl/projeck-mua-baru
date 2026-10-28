<?php

// app/Http/Requests/UpdateGaleriRequest.php

namespace App\Http\Requests;

class UpdateGaleriRequest extends StoreGaleriRequest
{
    // Foto jadi opsional saat update — sudah ditangani lewat isMethod('post') di parent.
}
