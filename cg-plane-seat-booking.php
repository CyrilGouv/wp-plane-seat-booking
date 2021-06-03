<?php
/**
 * Plugin Name: CG Plane Seat Booking
 * Description: A simple Plane seat booking plugin
 * Version: 1.0.0
 * Author: Cyril Gouv
 * Text Domain: cg-plane-seat-booking
 */


if (!defined('ABSPATH')) {
   echo 'Hey, que recherches-tu ?';
   exit;
}

class CgPlaneSeatBooking {
    public function __construct() {
        add_action( 'init', [$this, 'create_custom_post_type'] );

        add_action( 'wp_enqueue_scripts', [$this, 'load_scripts'] );

        add_shortcode( 'cg-plane-seat-booking', [$this, 'output'] );

        add_action( 'rest_api_init', [$this, 'register_rest_api'] );
    }

    public function create_custom_post_type() {
        $args = [
            'public' => true,
            'archive' => true,
            'show_ui' => true,
            'supports' => ['title'],
            'capability' => 'manage_options',
            'publicly_queryable' => true,
            'labels' => [
                'name' => 'Réservations',
                'singular_name' => 'Réservation',
                'add_new_item' => 'Ajoutez une réservation',
                'edit_item' => 'Éditez une réservation',
                'all_items' => 'Toutes les réservations'
            ],
            'menu_icon' => 'dashicons-airplane'
        ];

        register_post_type( 'plane_seat_booking', $args );
    }

    public function load_scripts() {
        wp_enqueue_style( 'plane-seat-booking-style', plugin_dir_url( __FILE__ ) . '/css/style.css' );
        wp_enqueue_script( 'jQuery', '//code.jquery.com/jquery-3.6.0.min.js' );
        wp_enqueue_script( 'plane-seat-booking-script', plugin_dir_url( __FILE__ ) . '/js/script.js', [], 1, true );
    }

    public function register_rest_api() {
        register_rest_route( 'plane-seat-booking/v1', 'booking', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_booking']
        ] );

        register_rest_route( 'plane-seat-booking/v1', 'booking', [
            'methods' => 'GET',
            'callback' => [$this, 'get_booking']
        ] );
    }

    public function get_booking() {
        $seatsQuery = new WP_Query([
            'post_type' => 'plane_seat_booking',
            'post_per_page' => -1
        ]);

        $results = [];

        while ($seatsQuery->have_posts()) {
            $seatsQuery->the_post();
            array_push( $results, [
                'title' => get_the_title(),
                'seat' => get_field( 'seat_id' )
            ] );
        }

        return $results;
    }
    

    public function handle_booking() {
        
        $content = trim(file_get_contents("php://input"));

        $data = json_decode($content, true);
        
        if (count($data) > 0) {
            foreach($data as $seat) {
                wp_insert_post([
                    'post_type' => 'plane_seat_booking',
                    'post_title' => 'Réservation pour le siège #' . $seat,
                    'meta_input' => [
                        'seat_id' => $seat
                    ],
                    'post_status' => 'publish' 
                ]);
            }

            echo json_encode([
                'status' => 'success',
                'message' => 'Merci de votre réservation'
            ]);

        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Merci de sélectionner au moins un siège'
            ]);
        }
    }

    public function output() {
        ?>
        <div class="movie-container">
            <h1>Sélectionnez votre place dans l'avion</h1>
        </div>

        <ul class="showcase">
            <li>
                <div class="seat"></div>
                <small>N/A</small>
            </li>
            <li>
                <div class="seat selected"></div>
                <small>Selected</small>
            </li>
            <li>
                <div class="seat occupied"></div>
                <small>Occupied</small>
            </li>
        </ul>
        
        
        <form class="container" id="form-plane-booking" data-url="<?= get_rest_url(null, 'plane-seat-booking/v1/booking') ?>">
            <div class="sec-class sec-class-b" data-price="150">
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
            </div>

            <div class="sec-class" data-price="150">
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
            </div>

            <div class="first-class" data-price="650">
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
    
                <div class="row">
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                    <div class="seat"></div>
                </div>
            </div>

            <input type="hidden" name="action" value="cg_booking">
            <button type="submit">Réserver</button>
            <?php wp_nonce_field( 'ajax-register-nonce', 'cg_plane_seat_booking' ); ?>
        </form>

        <p class="text">Vous avez sélectionné <span id="count">0</span> place(s) pour un total de <span id="total">0</span> €</p>
        <p class="text result"></p>
        <?php
    }
}

new CgPlaneSeatBooking;
