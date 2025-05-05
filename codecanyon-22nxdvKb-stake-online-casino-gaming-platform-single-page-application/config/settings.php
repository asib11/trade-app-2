<?php
/**
 *   1Stake iGaming Platform
 *   -----------------------
 *   settings.php
 * 
 *   @copyright  Copyright (c) 1stake, All rights reserved
 *   @author     1stake <sales@1stake.app>
 *   @see        https://1stake.app
*/

return [
    'theme' => [
        'default'   => env('THEME_DEFAULT', 'eclipseReverie'),
        'is_dark'   => env('THEME_IS_DARK', TRUE),
        'colors'    => [
            'background'            => env('THEME_COLOR_BACKGROUND', '#000000'),
            'app-bar'               => env('THEME_COLOR_APP_BAR', '#252628'),
            'system-bar'            => env('THEME_COLOR_SYSTEM_BAR', '#000000'),
            'surface'               => env('THEME_COLOR_SURFACE', '#252628'),
            'on-surface'            => env('THEME_COLOR_ON_SURFACE', '#E4E2E4'),
            'surface-light'         => env('THEME_COLOR_SURFACE_LIGHT', '#3B3B3D'),
            'surface-variant'       => env('THEME_COLOR_SURFACE_VARIANT', '#3B3B3D'),
            'on-surface-variant'    => env('THEME_COLOR_ON_SURFACE_VARIANT', '#E4E2E4'),
            'on-image'              => env('THEME_COLOR_ON_IMAGE', '#E4E2E4'),
            'footer'                => env('THEME_COLOR_FOOTER', '#252628'),
            'on-footer'             => env('THEME_COLOR_ON_FOOTER', '#E4E2E4'),
            'primary'               => env('THEME_COLOR_PRIMARY', '#0090ff'),
            'on-primary'            => env('THEME_COLOR_ON_PRIMARY', '#ffffff'),
            'primary-container'     => env('THEME_COLOR_PRIMARY_CONTAINER', '#0090ff'),
            'on-primary-container'  => env('THEME_COLOR_ON_PRIMARY_CONTAINER', '#ffffff'),
            'secondary'             => env('THEME_COLOR_SECONDARY', '#FF0090'),
            'on-secondary'          => env('THEME_COLOR_ON_SECONDARY', '#ffffff'),
            'secondary-container'   => env('THEME_COLOR_SECONDARY_CONTAINER', '#FF0090'),
            'on-secondary-container' => env('THEME_COLOR_ON_SECONDARY_CONTAINER', '#ffffff'),
            'info'                  => env('THEME_COLOR_INFO', '#00E4FF'),
            'warning'               => env('THEME_COLOR_WARNING', '#FFC107'),
            'success'               => env('THEME_COLOR_SUCCESS', '#11FF00'),
            'error'                 => env('THEME_COLOR_ERROR', '#FF1B00'),
            'on-error'              => env('THEME_COLOR_ON_ERROR', '#ffffff'),
        ],
        'background' => env('THEME_BACKGROUND', 'Circles'),
        'fonts' => [
            'body' => [
                'url' => env('THEME_FONT_BODY_URL', 'https://fonts.googleapis.com/css2?family=Goldman:wght@400;700&family=Play&display=swap'),
                'family' => env('THEME_FONT_BODY_FAMILY', 'Play'),
            ],
            'heading' => [
                'url' => env('THEME_FONT_HEADING_URL', ''),
                'family' => env('THEME_FONT_HEADING_FAMILY', 'Goldman'),
            ],
        ],
    ],

    'favicon' => [
        'ico' => env('FAVICON_ICO', '/images/favicon/favicon.ico'),
        'apple_touch' => env('FAVICON_APPLE_TOUCH', '/images/favicon/apple-touch.png'),
        '32x32' => env('FAVICON_32X32', '/images/favicon/32x32.png'),
        '192x192' => env('FAVICON_192X192', '/images/favicon/192x192.png'),
        'mask' => env('FAVICON_MASK', '/images/favicon/safari-pinned-tab.svg'),
    ],

    'format' => [
        'number' => [
            'decimal_separator'     => env('FORMAT_NUMBER_DECIMAL_SEPARATOR', ord('.')),
            'thousands_separator'   => env('FORMAT_NUMBER_THOUSANDS_SEPARATOR', ord(',')),
        ]
    ],

    'throttle' => [
        'api' => env('THROTTLE_API', 200),
    ],

    'cache' => [
        'leaderboard' => env('CACHE_LEADERBOARD', 60), 
        'admin' => [
            'dashboard' => env('CACHE_ADMIN_DASHBOARD', 60), 
        ],
    ],

    
    'users' => [
        'email_auth' => env('EMAIL_AUTH', TRUE),
        'email_verification' => env('EMAIL_VERIFICATION', FALSE),
        'create_random_avatar' => env('USERS_CREATE_RANDOM_AVATAR', TRUE),
        'cookie_consent_required' => env('USERS_COOKIE_CONSENT_REQUIRED', FALSE),
        'allow_name_change' => env('USERS_ALLOW_NAME_CHANGE', FALSE),
        'fields' => json_decode(env('USERS_FIELDS', json_encode([])))
    ],

    
    'tips' => [
        'enabled' => env('TIPS_ENABLED', TRUE),
        'max_amount' => env('TIPS_MAX_AMOUNT', 500),
        'interval' => env('TIPS_INTERVAL', 24),
    ],

    
    'affiliate' => [
        'allow_same_ip' => env('AFFILIATE_ALLOW_SAME_IP', FALSE),

        'auto_approval_frequency' => env('AFFILIATE_AUTO_APPROVAL_FREQUENCY', 'weekly'),

        'currency' => env('AFFILIATE_CURRENCY'),

        
        'commissions' => [
            1  => json_decode(env('AFFILIATE_COMMISSIONS_SIGN_UP', json_encode([100, 50, 25]))),
            6  => json_decode(env('AFFILIATE_COMMISSIONS_EMAIL_VERIFICATION', json_encode([50, 25, 10]))),
            7  => json_decode(env('AFFILIATE_COMMISSIONS_KYC', json_encode([50, 25, 10]))),
            2  => json_decode(env('AFFILIATE_COMMISSIONS_GAME_LOSS', json_encode([10, 5, 1]))),
            3  => json_decode(env('AFFILIATE_COMMISSIONS_GAME_WIN', json_encode([10, 5, 1]))),
            5  => json_decode(env('AFFILIATE_COMMISSIONS_RAFFLE_TICKET', json_encode([20, 10, 5]))),
            4  => json_decode(env('AFFILIATE_COMMISSIONS_DEPOSIT', json_encode([5, 3, 1]))),
        ],
    ],

    'kyc' => [
        'enabled' => env('KYC_ENABLED', FALSE),
        'provider' => env('KYC_PROVIDER', 'manual'),
        'pages' => json_decode(env('KYC_PAGES', json_encode(['game', 'cashback', 'faucet', 'deposit', 'withdrawal']))),
    ],

    'notifications' => [
        'admin' => [
            'email' => env('NOTIFICATIONS_ADMIN_EMAIL', ''),
            'data_deletion_email' => env('NOTIFICATIONS_ADMIN_DATA_DELETION_EMAIL', !app()->runningInConsole() ? 'info@' . request()->getHost() : ''),
            'registration' => [
                'enabled' => env('NOTIFICATIONS_ADMIN_REGISTRATION_ENABLED', FALSE),
            ],
            'kyc' => [
                'enabled' => env('NOTIFICATIONS_ADMIN_KYC_ENABLED', FALSE),
            ],
            'game' => [
                'win' => [
                    'enabled' => env('NOTIFICATIONS_ADMIN_GAME_WIN_ENABLED', FALSE),
                    'treshold' => env('NOTIFICATIONS_ADMIN_GAME_WIN_TRESHOLD', 1000),
                ],
                'loss' => [
                    'enabled' => env('NOTIFICATIONS_ADMIN_GAME_LOSS_ENABLED', FALSE),
                    'treshold' => env('NOTIFICATIONS_ADMIN_GAME_LOSS_TRESHOLD', 1000),
                ]
            ],
        ],
    ],

    
    'games' => [
        'playing_cards' => [
            'front_image' => env('GAMES_PLAYING_CARDS_FRONT_IMAGE', '/images/games/playing-cards/front.svg'),
            'back_image' => env('GAMES_PLAYING_CARDS_BACK_IMAGE', '/images/games/playing-cards/back.svg'),
            'deck' => env('GAMES_PLAYING_CARDS_DECK', 'poker'),
        ],
        'multiplayer' => [
            
            'rooms_creation_limit' => env('GAMES_MULTIPLAYER_ROOMS_CREATION_LIMIT', 2)
        ]
    ],

    
    'bots' => [
        'games' => [
            
            
            'frequency' => env('BOTS_GAMES_FREQUENCY', 'hourly'),
            'min_count' => env('BOTS_GAMES_MIN_COUNT', 1),
            'max_count' => env('BOTS_GAMES_MAX_COUNT', 10),
            'min_bet'   => env('BOTS_GAMES_MIN_BET'),
            'max_bet'   => env('BOTS_GAMES_MAX_BET'),
        ]
    ],

    'interface' => [
        'navbar' => [
            'visible' => env('INTERFACE_NAVBAR_VISIBLE', FALSE),
        ],
        'online' => [
            'enabled' => env('INTERFACE_ONLINE_ENABLED', FALSE),
        ],
        'app_bar' => [
            'search_enabled' => env('INTERFACE_APP_BAR_SEARCH_ENABLED', FALSE),
        ],
        'system_bar' => [
            'enabled' => env('INTERFACE_SYSTEM_BAR_ENABLED', FALSE),
        ],
        'chat' => [
            'enabled' => env('CHAT_ENABLED', FALSE),
            'visible' => env('CHAT_VISIBLE', FALSE),
            'message_max_length' => env('CHAT_MESSAGE_MAX_LENGTH', 150)
        ],
        'locale' => [
            'detect_from_browser' => env('INTERFACE_LOCALE_DETECT_FROM_BROWSER', TRUE),
            'can_be_changed' => env('INTERFACE_LOCALE_CAN_BE_CHANGED', TRUE),
        ],
        'sound' => [
            'enabled_by_default' => env('INTERFACE_SOUND_ENABLED_BY_DEFAULT', TRUE),
        ],
        'game_feed' => [
            'enabled' => env('INTERFACE_GAME_FEED_ENABLED', TRUE),
            'enabled_by_default' => env('INTERFACE_GAME_FEED_ENABLED_BY_DEFAULT', TRUE),
            'delay' => env('INTERFACE_GAME_FEED_DELAY', 10),
            'timeout' => env('INTERFACE_GAME_FEED_TIMEOUT', 10)
        ]
    ],

    'content' => [
        'home' => [
            'slider' => json_decode(env('CONTENT_HOME_SLIDER', json_encode([
                'height' => 600,
                'height_mobile' => 300,
                'navigation' => TRUE,
                'pagination' => FALSE,
                'overlay' => TRUE,
                'cycle' => TRUE,
                'interval' => 5, 
                'slides' => [
                    [
                        'title' => '1Stake',
                        'subtitle' => 'Fair online casino games',
                        'image' => [
                            'url' => '/images/home/banner.jpg',
                        ],
                        'link' => [
                            'title' => '',
                            'url' => '',
                        ]
                    ],
                    [
                        'title' => 'Test your luck',
                        'subtitle' => 'Play our games and win big time!',
                        'image' => [
                            'url' => '/images/home/banner2.jpg',
                        ],
                        'link' => [
                            'title' => 'I want to try',
                            'url' => '/register',
                        ]
                    ]
                ]
            ]))),
            'card_classes' => env('CONTENT_HOME_CARD_CLASSES', 'v-col-6 v-col-sm-4 v-col-md-4 v-col-lg-3 v-col-xl-2'),
            'games' => [
                'display_style' => env('CONTENT_HOME_GAMES_DISPLAY_STYLE', 'card'),
                'filter' => env('CONTENT_HOME_GAMES_FILTER', FALSE),
                'display_count' => env('CONTENT_HOME_GAMES_DISPLAY_COUNT', 12),
                'load_count' => env('CONTENT_HOME_GAMES_LOAD_COUNT', 6),
            ],
            'provider_games' => [
                'display_style' => env('CONTENT_HOME_PROVIDER_GAMES_DISPLAY_STYLE', 'thumb'),
                'display_count' => env('CONTENT_HOME_PROVIDER_GAMES_DISPLAY_COUNT', 6),
                'load_count' => env('CONTENT_HOME_PROVIDER_GAMES_LOAD_COUNT', 6),
                'featured_categories' => json_decode(env('CONTENT_HOME_PROVIDER_GAMES_FEATURED_CATEGORIES', json_encode([
                    ['title' => 'Slots', 'icon' => 'mdi-slot-machine', 'categories' => ['Slot', 'Slots']],
                ])), JSON_OBJECT_AS_ARRAY),
                'sort_game_id_reverse' => env('CONTENT_HOME_PROVIDER_GAMES_SORT_GAME_ID_REVERSE', TRUE),
            ],
            'raffles' => [
                'display_style' => env('CONTENT_HOME_RAFFLES_DISPLAY_STYLE', 'card'),
            ]
        ],
        'social' => json_decode(env('CONTENT_SOCIAL', json_encode([
            [
                'title' => 'Send us an email',
                'icon' => 'mdi-at',
                'color' => 'rgb(166, 166, 166)',
                'url' => '/'
            ],
            [
                'title' => 'Follow us on Facebook',
                'icon' => 'mdi-facebook',
                'color' => '#4267B2',
                'url' => '/'
            ],
            [
                'title' => 'Follow us on Instagram',
                'icon' => 'mdi-instagram',
                'color' => '#C13584',
                'url' => '/'
            ],
            [
                'title' => 'Follow us on Twitter',
                'icon' => 'mdi-twitter',
                'color' => '#00acee',
                'url' => '/'
            ],
            [
                'title' => 'Follow us on Twitch',
                'icon' => 'mdi-twitch',
                'color' => '#6441a5',
                'url' => '/'
            ]
        ]))),
        'pages' => json_decode(env('CONTENT_PAGES', json_encode([
                [
                    'id' => 'provably-fair',
                    'title' => 'Provably fair',
                    'icon' => 'mdi-certificate'
                ],
                [
                    'id' => 'privacy-policy',
                    'title' => 'Privacy policy',
                    'icon' => 'mdi-lock'
                ],
                [
                    'id' => 'terms-of-use',
                    'title' => 'Terms of use',
                    'icon' => 'mdi-file-document'
                ],
        ]))),
        'menu' => [
            'main' => json_decode(env('CONTENT_MENU_MAIN', json_encode(['history.recent', 'history.wins', 'history.losses', 'leaderboard']))),
            'footer' => json_decode(env('CONTENT_MENU_FOOTER', json_encode(['/pages/provably-fair', '/pages/privacy-policy', '/pages/terms-of-use']))),
        ]
    ],
];
