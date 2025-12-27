<?php

namespace LifePlus\SDK;

/**
 * Helper functions for working with the LifePlus SDK
 * 
 * @package LifePlus\SDK
 */
class Helpers
{
    /**
     * Safely get a value from an array with a default
     * 
     * @param array $array The array to search
     * @param string $key The key to look for
     * @param mixed $default Default value if key not found
     * @return mixed The value or default
     */
    public static function arrayGet(array $array, string $key, $default = null)
    {
        return $array[$key] ?? $default;
    }

    /**
     * Safely get a string value, returns empty string if null
     * 
     * @param string|null $value The value to check
     * @return string The value or empty string
     */
    public static function stringOrEmpty(?string $value): string
    {
        return $value ?? '';
    }

    /**
     * Safely get an integer value, returns 0 if null
     * 
     * @param int|null $value The value to check
     * @return int The value or 0
     */
    public static function intOrZero(?int $value): int
    {
        return $value ?? 0;
    }

    /**
     * Safely get a float value, returns 0.0 if null
     * 
     * @param float|null $value The value to check
     * @return float The value or 0.0
     */
    public static function floatOrZero(?float $value): float
    {
        return $value ?? 0.0;
    }

    /**
     * Safely get a boolean value, returns false if null
     * 
     * @param bool|null $value The value to check
     * @return bool The value or false
     */
    public static function boolOrFalse(?bool $value): bool
    {
        return $value ?? false;
    }

    /**
     * Format Bangladeshi phone number
     * 
     * @param string $phone Phone number
     * @return string Formatted phone number
     */
    public static function formatPhone(string $phone): string
    {
        // Remove spaces, dashes, and other non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove country code if present
        if (str_starts_with($phone, '880')) {
            $phone = substr($phone, 3);
        }
        
        // Ensure it starts with 0
        if (!str_starts_with($phone, '0')) {
            $phone = '0' . $phone;
        }
        
        return $phone;
    }

    /**
     * Format price in BDT currency
     * 
     * @param float $amount Amount to format
     * @param bool $showCurrency Include currency symbol
     * @return string Formatted price
     */
    public static function formatPrice(float $amount, bool $showCurrency = true): string
    {
        $formatted = number_format($amount, 2);
        return $showCurrency ? "BDT {$formatted}" : $formatted;
    }

    /**
     * Convert object to array recursively
     * 
     * @param mixed $obj Object to convert
     * @return mixed Array representation
     */
    public static function objectToArray($obj)
    {
        if (is_object($obj)) {
            $obj = (array) $obj;
        }
        if (is_array($obj)) {
            return array_map([self::class, 'objectToArray'], $obj);
        }
        return $obj;
    }

    /**
     * Check if string is valid JSON
     * 
     * @param string $string String to check
     * @return bool True if valid JSON
     */
    public static function isJson(string $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Generate a unique request ID
     * 
     * @return string Unique ID
     */
    public static function generateRequestId(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
