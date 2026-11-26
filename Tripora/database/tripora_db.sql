-- =============================================================
-- TRIPORA v1.0 | MySQL / MariaDB (XAMPP)
-- Import this file from phpMyAdmin > Import.
-- Demo administrator: admin@tripora.local / TriporaAdmin!2026
-- Change the demo password before any real deployment.
-- =============================================================

CREATE DATABASE IF NOT EXISTS tripora_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tripora_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS packages;
DROP TABLE IF EXISTS destinations;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(25) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_email (email),
    KEY idx_users_role (role)
) ENGINE=InnoDB;

CREATE TABLE categories (
    category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,
    image_url VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_categories_name (category_name)
) ENGINE=InnoDB;

CREATE TABLE destinations (
    destination_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    destination_name VARCHAR(150) NOT NULL,
    country VARCHAR(100) NOT NULL,
    city VARCHAR(100) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    image_url VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_destinations_name (destination_name),
    KEY idx_destinations_country (country)
) ENGINE=InnoDB;

CREATE TABLE packages (
    package_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    destination_id INT UNSIGNED NOT NULL,
    package_name VARCHAR(150) NOT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT 0.00,
    duration VARCHAR(50) DEFAULT NULL,
    facilities TEXT DEFAULT NULL,
    itinerary TEXT DEFAULT NULL,
    image_url VARCHAR(500) DEFAULT NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_packages_category (category_id),
    KEY idx_packages_destination (destination_id),
    KEY idx_packages_status (status),
    KEY idx_packages_name (package_name),
    CONSTRAINT fk_packages_category FOREIGN KEY (category_id)
        REFERENCES categories(category_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_packages_destination FOREIGN KEY (destination_id)
        REFERENCES destinations(destination_id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Seed administrator. The password is stored as a PHP password_hash() hash.
INSERT INTO users (full_name, email, phone, password, role) VALUES
('Tripora Administrator', 'admin@tripora.local', NULL,
 '$2y$12$iZ43m7.zIM7hV.0TaOdlRuMUVNhK9yYShem/sZFXDJq3nslhvvxBm', 'admin');

INSERT INTO categories (category_name, description, image_url) VALUES
('Nature & Adventure', 'Explore mountains, forests, and outdoor adventures.', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=900'),
('Beach & Island', 'Discover beaches, islands, and tropical destinations.', 'https://images.unsplash.com/photo-1519046904884-53103b34b206?w=900'),
('Culture & Heritage', 'Experience local culture, traditions, and historic places.', 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=900'),
('International Trip', 'Explore destinations and experiences around the world.', 'https://images.unsplash.com/photo-1500534623283-312aade485b7?w=900');

INSERT INTO destinations (destination_name, country, city, description, image_url) VALUES
('Bali', 'Indonesia', 'Denpasar', 'A tropical island known for beautiful beaches, temples, rice terraces, and warm hospitality.', 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1200'),
('Mount Bromo', 'Indonesia', 'Probolinggo', 'A spectacular volcanic landscape famous for sunrise viewpoints and sea-of-sand scenery.', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1200'),
('Raja Ampat', 'Indonesia', 'Waisai', 'An archipelago celebrated for turquoise waters, island scenery, and rich marine biodiversity.', 'https://images.unsplash.com/photo-1519046904884-53103b34b206?w=1200'),
('Kyoto', 'Japan', 'Kyoto', 'A historic city with traditional streets, temples, gardens, and distinctive Japanese culture.', 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=1200'),
('Labuan Bajo', 'Indonesia', 'Labuan Bajo', 'A gateway to island adventures, dramatic coastlines, and Komodo National Park.', 'https://images.unsplash.com/photo-1518837695005-2083093ee35b?w=1200'),
('Yogyakarta', 'Indonesia', 'Yogyakarta', 'A cultural destination known for heritage sites, art, food, and nearby temples.', 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=1200');

INSERT INTO packages
(category_id, destination_id, package_name, description, price, duration, facilities, itinerary, image_url, status)
VALUES
(2, 1, 'Bali Tropical Escape', 'Enjoy Bali beaches, local attractions, and tropical scenery in a relaxed short getaway.', 2500000.00, '3 Days 2 Nights', 'Hotel accommodation; Local transport; Breakfast; Local guide', 'Day 1: Arrival and hotel check-in\nDay 2: Beach visit and cultural tour\nDay 3: Breakfast and departure', 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1200', 'active'),
(1, 2, 'Bromo Sunrise Adventure', 'Experience the sunrise and dramatic volcanic scenery of Mount Bromo.', 1200000.00, '2 Days 1 Night', 'Jeep tour; Local guide; Accommodation; Mineral water', 'Day 1: Arrival and check-in\nDay 2: Sunrise viewpoint, sea of sand, and Bromo area tour', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=1200', 'active'),
(2, 3, 'Raja Ampat Discovery', 'Discover tropical islands and marine landscapes in the beautiful Raja Ampat archipelago.', 5500000.00, '4 Days 3 Nights', 'Boat transfer; Accommodation; Local guide; Island tour', 'Day 1: Arrival and transfer\nDay 2: Island exploration\nDay 3: Snorkeling and sightseeing\nDay 4: Departure', 'https://images.unsplash.com/photo-1519046904884-53103b34b206?w=1200', 'active'),
(4, 4, 'Kyoto Cultural Journey', 'Explore historic landmarks, traditional neighborhoods, and Japanese culture in Kyoto.', 8500000.00, '5 Days 4 Nights', 'Hotel; Local guide; City tour; Breakfast', 'Day 1: Arrival\nDay 2: Temple and garden tour\nDay 3: Cultural experience\nDay 4: City exploration\nDay 5: Departure', 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=1200', 'active'),
(2, 5, 'Labuan Bajo Island Hopping', 'Sail across scenic islands and enjoy the coastal beauty around Labuan Bajo.', 3900000.00, '3 Days 2 Nights', 'Boat trip; Hotel; Local guide; Lunch during tour', 'Day 1: Arrival\nDay 2: Island hopping and viewpoints\nDay 3: Breakfast and departure', 'https://images.unsplash.com/photo-1518837695005-2083093ee35b?w=1200', 'active'),
(3, 6, 'Yogyakarta Heritage Trail', 'Discover heritage, local food, and cultural attractions around Yogyakarta.', 1800000.00, '3 Days 2 Nights', 'Hotel; Local transport; Guide; Breakfast', 'Day 1: Arrival and city walk\nDay 2: Heritage and temple visit\nDay 3: Local market and departure', 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=1200', 'active');

-- Quick verification query:
-- SELECT p.package_id, p.package_name, c.category_name,
--        d.destination_name, d.country, p.price, p.duration, p.status
-- FROM packages p
-- JOIN categories c ON c.category_id = p.category_id
-- JOIN destinations d ON d.destination_id = p.destination_id;
