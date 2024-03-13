<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ClientHelper
{
    public static function checkUserByID($userID)
    {
        try {
            $url = env("URL_SERVICE_USER") . "/users/" . $userID;
            $response = Http::timeout(5)->get($url);

            return $response->status() === 200 ? true : false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function checkCourseByID($courseID)
    {
        try {
            $url = env("URL_SERVICE_COURSE") . "/courses/" . $courseID;
            $response = Http::timeout(5)->get($url);

            return $response->status() === 200 ? true : false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public static function getCourseByID($courseID)
    {
        try {
            $url = env("URL_SERVICE_COURSE") . "/courses/" . $courseID;
            $response = Http::timeout(5)->get($url);

            if ($response->status() === 200) {
                return $response->json();
            }

            return null;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public static function giveCourseAccessToUser($courseID, $userID)
    {
        try {
            $url = env("URL_SERVICE_COURSE") . "/my-courses/store";
            $response = Http::post($url, [
                'course_id' => $courseID,
                'user_id' => $userID
            ]);

            if ($response->status == 201) {
                return true;
            }

            return false;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
