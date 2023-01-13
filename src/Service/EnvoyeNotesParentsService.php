<?php

namespace App\Service;

class EnvoieNotesParents
{

    public function envoyeNotes($eleveNom, $elevePrenom, $note, $devoir, $matiere, $numeroParent)
    {

        $message = "Votre Enfant $eleveNom $elevePrenom a eu $note en $matiere $devoir.";

        $url = "https://www.aqilas.com/api/v1/sms";

        $data = [
            "from" => "Sabidani",
            "to" => [$numeroParent],
            "text" => $message
        ];
        $dataJson = json_encode($data);
        $header = [
            'Content-Type: application/json',
            "X-AUTH-TOKEN: bca6852f-1703-4417-bac4-411712e3e5b0"
        ];

        $apiUrl = curl_init($url);
        curl_setopt($apiUrl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($apiUrl, CURLOPT_POSTFIELDS, $dataJson);
        curl_setopt($apiUrl, CURLOPT_RETURNTRANSFER, true);

        curl_exec($apiUrl);

        curl_close($apiUrl);
    }
}
