CREATE DATABASE IF NOT EXISTS hotel_recommendation
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE hotel_recommendation;

CREATE TABLE users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(255) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  phone      VARCHAR(20),
  role       ENUM('super_admin', 'admin_hotel', 'user') NOT NULL DEFAULT 'user',
  active     TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE hotels (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(255) NOT NULL,
  address     TEXT,
  latitude    DECIMAL(10,7) DEFAULT 0,
  longitude   DECIMAL(10,7) DEFAULT 0,
  price_start DECIMAL(12,2) DEFAULT 0,
  admin_id    INT NOT NULL,
  status      ENUM('pending', 'verified', 'rejected') NOT NULL DEFAULT 'pending',
  rating_avg  DECIMAL(2,1) DEFAULT 0.0,
  image       VARCHAR(255) DEFAULT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE facilities (
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE hotel_facilities (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  hotel_id    INT NOT NULL,
  facility_id INT NOT NULL,
  FOREIGN KEY (hotel_id)    REFERENCES hotels(id)    ON DELETE CASCADE,
  FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE,
  UNIQUE KEY (hotel_id, facility_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE rooms (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  hotel_id      INT NOT NULL,
  room_type     VARCHAR(100) NOT NULL,
  price         DECIMAL(12,2) NOT NULL DEFAULT 0,
  total_room    INT NOT NULL DEFAULT 0,
  occupied_room INT NOT NULL DEFAULT 0,
  is_active     TINYINT(1) NOT NULL DEFAULT 1,
  status        ENUM('available', 'full') NOT NULL DEFAULT 'available',
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE bookings (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT NOT NULL,
  room_id     INT NOT NULL,
  check_in    DATE NOT NULL,
  check_out   DATE NOT NULL,
  total_price DECIMAL(12,2) NOT NULL DEFAULT 0,
  status      ENUM('pending', 'confirmed', 'checked_out', 'cancelled') NOT NULL DEFAULT 'pending',
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE reviews (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NOT NULL,
  hotel_id   INT NOT NULL,
  rating     TINYINT NOT NULL DEFAULT 5,
  comment    TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
  FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE recommendations (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  user_id       INT NOT NULL,
  hotel_id      INT NOT NULL,
  entropy_score DECIMAL(10,5) NOT NULL DEFAULT 0.00000,
  topsis_score  DECIMAL(10,5) NOT NULL DEFAULT 0.00000,
  `rank`        INT NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
  FOREIGN KEY (hotel_id) REFERENCES hotels(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

