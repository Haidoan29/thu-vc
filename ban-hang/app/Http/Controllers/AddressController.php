<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function getDistricts($provinceCode)
    {
        $response = file_get_contents("https://provinces.open-api.vn/api/p/{$provinceCode}?depth=2");
        $data = json_decode($response, true);

        return response()->json([
            'districts' => $data['districts'] ?? []
        ]);
    }

    public function getWards($districtCode)
    {
        $response = file_get_contents("https://provinces.open-api.vn/api/d/{$districtCode}?depth=2");
        $data = json_decode($response, true);

        return response()->json([
            'wards' => $data['wards'] ?? []
        ]);
    }
}
