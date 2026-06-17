<?php
/**
 * StockVision - Route Definitions
 * Maps URL patterns to Controller@action
 *
 * Format: ['method', 'path', 'controller', 'action']
 * Use {id} for route parameters
 */

return [
    // ── Authentication ──────────────────────────────────────
    ['GET',  '/login',              'AuthController',      'showLogin'],
    ['POST', '/login',              'AuthController',      'login'],
    ['POST', '/logout',             'AuthController',      'logout'],
    ['GET',  '/profile',            'AuthController',      'profile'],
    ['POST', '/profile',            'AuthController',      'updateProfile'],
    ['GET',  '/change-password',    'AuthController',      'showChangePassword'],
    ['POST', '/change-password',    'AuthController',      'changePassword'],

    // ── Dashboard ───────────────────────────────────────────
    ['GET',  '/',                   'DashboardController', 'index'],

    // ── Products ────────────────────────────────────────────
    ['GET',  '/products',           'ProductController',   'index'],
    ['GET',  '/products/create',    'ProductController',   'create'],
    ['POST', '/products/store',     'ProductController',   'store'],
    ['GET',  '/products/{id}',      'ProductController',   'show'],
    ['GET',  '/products/{id}/edit', 'ProductController',   'edit'],
    ['POST', '/products/{id}/update',  'ProductController','update'],
    ['POST', '/products/{id}/delete',  'ProductController','delete'],

    // ── Categories ──────────────────────────────────────────
    ['GET',  '/categories',             'CategoryController', 'index'],
    ['GET',  '/categories/create',      'CategoryController', 'create'],
    ['POST', '/categories/store',       'CategoryController', 'store'],
    ['GET',  '/categories/{id}/edit',   'CategoryController', 'edit'],
    ['POST', '/categories/{id}/update', 'CategoryController', 'update'],
    ['POST', '/categories/{id}/delete', 'CategoryController', 'delete'],

    // ── Suppliers ───────────────────────────────────────────
    ['GET',  '/suppliers',              'SupplierController', 'index'],
    ['GET',  '/suppliers/create',       'SupplierController', 'create'],
    ['POST', '/suppliers/store',        'SupplierController', 'store'],
    ['GET',  '/suppliers/{id}',         'SupplierController', 'show'],
    ['GET',  '/suppliers/{id}/edit',    'SupplierController', 'edit'],
    ['POST', '/suppliers/{id}/update',  'SupplierController', 'update'],
    ['POST', '/suppliers/{id}/delete',  'SupplierController', 'delete'],

    // ── Stock Movements ─────────────────────────────────────
    ['GET',  '/stock',              'StockController',     'index'],
    ['GET',  '/stock/in',           'StockController',     'inForm'],
    ['POST', '/stock/in',           'StockController',     'storeIn'],
    ['GET',  '/stock/out',          'StockController',     'outForm'],
    ['POST', '/stock/out',          'StockController',     'storeOut'],

    // ── Alerts ──────────────────────────────────────────────
    ['GET',  '/alerts',             'AlertController',     'index'],
    ['GET',  '/api/alerts/count',   'AlertController',     'count'],

    // ── Users (Admin Only) ──────────────────────────────────
    ['GET',  '/users',              'UserController',      'index'],
    ['GET',  '/users/create',       'UserController',      'create'],
    ['POST', '/users/store',        'UserController',      'store'],
    ['GET',  '/users/{id}/edit',    'UserController',      'edit'],
    ['POST', '/users/{id}/update',  'UserController',      'update'],
    ['POST', '/users/{id}/delete',  'UserController',      'delete'],
];
