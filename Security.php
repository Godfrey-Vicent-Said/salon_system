<?php
// Security.php

class Security {
    // Ufunguo wa siri (Encryption Key) wenye herufi 32. Huu utatumika kufunga na kufungua data.
    private static $encryption_key = "sAlOn_SyStEm_S3cr3t_K3y_F0r_2026!"; 
    private static $method = "AES-256-CBC";

    // 1. Mbinu ya kufunga data (Encryption)
    public static function encrypt($data) {
        if (empty($data)) return $data;
        
        $iv_length = openssl_cipher_iv_length(self::$method);
        $iv = openssl_random_pseudo_bytes($iv_length);
        
        $encrypted_data = openssl_encrypt($data, self::$method, self::$encryption_key, 0, $iv);
        
        // MAREKEBISHO: Tunageuza IV kuwa hex ili iwe salama kutenganishwa kwa '::' bila kuvuruga binary bytes
        return base64_encode(bin2hex($iv) . '::' . $encrypted_data);
    }

    // 2. Mbinu ya kufungua data (Decryption)
    public static function decrypt($data) {
        if (empty($data)) return $data;
        
        $decrypted_raw = base64_decode($data);
        if (strpos($decrypted_raw, '::') === false) return $data; 
        
        list($iv_hex, $encrypted_data) = explode('::', $decrypted_raw, 2);
        
        // MAREKEBISHO: Tunairudisha hex kuwa binary halisi ya IV kabla ya kufungua data
        $iv = hex2bin($iv_hex);
        
        // Kulinda urefu wa IV usilete makosa
        $iv_length = openssl_cipher_iv_length(self::$method);
        if (strlen($iv) !== $iv_length) {
            return $data;
        }
        
        return openssl_decrypt($encrypted_data, self::$method, self::$encryption_key, 0, $iv);
    }
}
?>