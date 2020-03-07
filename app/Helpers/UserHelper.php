<?php

if (!function_exists('checkEmail')) {

    /**
     * description
     *
     * @param string $email
     * @return string
     */
    function checkEmail($email)
    {
        list($username, $domain) = explode('@', $email);

        if ($domain == 'gmail.com') {
            $username = str_replace('.', '', $username);

            $email = $username . '@' . $domain;
        }

        return $email;
    }
}

if (!function_exists('checkPhone')) {

    /**
     * description
     *
     * @param string $phone
     * @return string
     */
    function checkPhone($phone)
    {
        $phone_dot = str_replace('.', '', $phone);
        $phone_space = str_replace(' ', '', $phone_dot);
        $phone_id = $phone_space[0] . $phone_space[1];
        $phone_id_2 = $phone_space[0] . $phone_space[1] . $phone_space[2];
        $phone_id_3 = $phone_space[0];

        if ($phone_id == '08') {
            $phone_space = '628' . substr($phone_space, 2);
        }

        if ($phone_id_2 == '+62') {
            $phone_space = '62' . substr($phone_space, 3);
        }

        if ($phone_id_3 == '8') {
            $phone_space = '628' . substr($phone_space, 1);
        }

        return $phone_space;
    }
}
