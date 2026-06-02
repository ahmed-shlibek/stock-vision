<?php
/**
 * StockVision - Formatting Helpers
 * Functions for formatting numbers, currency, and dates
 */

/**
 * Format a number as currency (USD)
 */
function formatCurrency(float $amount): string
{
    return '$' . number_format($amount, 2);
}

/**
 * Format a number with commas
 */
function formatNumber(int|float $number): string
{
    return number_format($number);
}

/**
 * Format date string to human-readable format
 */
function formatDate(string $dateString, string $format = 'M j, Y h:i A'): string
{
    $timestamp = strtotime($dateString);
    if (!$timestamp) return '';
    return date($format, $timestamp);
}
