CREATE DATABASE IF NOT EXISTS urbannest_estate
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE urbannest_estate;

DROP TABLE IF EXISTS property_requests;
DROP TABLE IF EXISTS properties;
DROP TABLE IF EXISTS property_types;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  phone VARCHAR(40) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
  created_at DATETIME NOT NULL
) ENGINE=InnoDB;

CREATE TABLE property_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  description TEXT,
  created_at DATETIME NOT NULL
) ENGINE=InnoDB;

CREATE TABLE properties (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  property_type_id INT NOT NULL,
  deal_type ENUM('sale', 'rent') NOT NULL,
  city VARCHAR(100) NOT NULL,
  district VARCHAR(120) NOT NULL,
  address VARCHAR(180) NOT NULL,
  rooms INT NOT NULL DEFAULT 0,
  area DECIMAL(8,2) NOT NULL,
  floor INT NOT NULL DEFAULT 0,
  total_floors INT NOT NULL DEFAULT 0,
  price DECIMAL(12,2) NOT NULL,
  status ENUM('available', 'reserved', 'sold', 'rented', 'hidden') NOT NULL DEFAULT 'available',
  image VARCHAR(500) NOT NULL,
  description TEXT NOT NULL,
  created_at DATETIME NOT NULL,
  updated_at DATETIME NOT NULL,
  CONSTRAINT fk_properties_type FOREIGN KEY (property_type_id) REFERENCES property_types(id)
) ENGINE=InnoDB;

CREATE TABLE property_requests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  property_id INT NOT NULL,
  name VARCHAR(120) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  email VARCHAR(160) NOT NULL,
  message TEXT,
  preferred_contact_time VARCHAR(120) NOT NULL,
  status ENUM('new', 'contacted', 'scheduled', 'closed', 'cancelled') NOT NULL DEFAULT 'new',
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_property_requests_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_property_requests_property FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (name, email, phone, password_hash, role, created_at) VALUES
('Splavski Maxim', 'admin@urbannest.test', '+373 600 20 101', '$2y$10$rigoI8H00N341IND/kg3mOXQ0kIcvBAGl2uqMscGn5e7BctJkbmze', 'admin', NOW()),
('Metelska Daniela', 'metelska.admin@urbannest.test', '+373 600 20 102', '$2y$10$rigoI8H00N341IND/kg3mOXQ0kIcvBAGl2uqMscGn5e7BctJkbmze', 'admin', NOW()),
('Irina Popa', 'user@urbannest.test', '+373 681 45 220', '$2y$10$muuA.u3GFnnYr7A8sfEJDelbZCn6Vlh0oS1S8LMwijOqfbuOEsQVm', 'user', NOW());

INSERT INTO property_types (name, description, created_at) VALUES
('Apartment', 'Современные квартиры для жизни и инвестиций.', NOW()),
('House', 'Частные дома и таунхаусы с приватной территорией.', NOW()),
('Commercial', 'Коммерческие помещения и офисы для бизнеса.', NOW()),
('Land', 'Участки под строительство или инвестиционные проекты.', NOW()),
('New Building', 'Объекты в современных жилых комплексах.', NOW());

INSERT INTO properties (title, property_type_id, deal_type, city, district, address, rooms, area, floor, total_floors, price, status, image, description, created_at, updated_at) VALUES
('Central Residence Apartment', 1, 'sale', 'Chisinau', 'Centru', 'str. Bucuresti 42', 3, 86.50, 7, 12, 168000.00, 'available', 'assets/img/properties/apartment-central-residence.jpg', 'Светлая квартира в центральной зоне города с продуманной планировкой, просторной гостиной и быстрым доступом к деловой инфраструктуре.', NOW(), NOW()),
('Botanica New Building Flat', 5, 'sale', 'Chisinau', 'Botanica', 'bd. Dacia 58', 2, 64.20, 9, 14, 118500.00, 'available', 'assets/img/properties/new-building-botanica.jpg', 'Квартира в новом жилом комплексе с подземным паркингом, закрытым двором и спокойной современной архитектурой.', NOW(), NOW()),
('Telecentru Family House', 2, 'sale', 'Chisinau', 'Telecentru', 'str. Miorita 18', 5, 184.00, 2, 2, 295000.00, 'reserved', 'assets/img/properties/house-telecentru-family.jpg', 'Дом для семьи с приватным двором, кабинетом, несколькими спальнями и удобным выездом в центральную часть города.', NOW(), NOW()),
('Riscani Business Office', 3, 'rent', 'Chisinau', 'Riscani', 'str. Kiev 9', 0, 142.00, 4, 8, 1850.00, 'available', 'assets/img/properties/office-riscani-business.jpg', 'Офисное пространство с переговорной, open-space зоной и нейтральной отделкой для команды среднего размера.', NOW(), NOW()),
('Buiucani Quiet Studio', 1, 'rent', 'Chisinau', 'Buiucani', 'str. Alba Iulia 77', 1, 38.00, 5, 10, 520.00, 'available', 'assets/img/properties/studio-buiucani-quiet.jpg', 'Компактная студия для аренды с аккуратной кухней, рабочим местом и удобным доступом к общественному транспорту.', NOW(), NOW()),
('Durleshti Residential Land', 4, 'sale', 'Durlesti', 'Poiana Domneasca', 'str. Codrilor 12', 0, 620.00, 0, 0, 79000.00, 'available', 'assets/img/properties/land-durlesti-residential.jpg', 'Участок под строительство частного дома в спокойной жилой зоне с доступом к коммуникациям и перспективой роста стоимости.', NOW(), NOW()),
('Premium Townhouse Codru', 2, 'sale', 'Codru', 'Centru', 'str. Grenoble 126', 4, 156.00, 2, 2, 238000.00, 'sold', 'assets/img/properties/townhouse-codru-premium.jpg', 'Современный таунхаус с террасой, двумя парковочными местами и практичной планировкой для городской семьи.', NOW(), NOW()),
('Ground Floor Retail Space', 3, 'rent', 'Chisinau', 'Centru', 'str. Puskin 31', 0, 96.00, 1, 6, 2100.00, 'rented', 'assets/img/properties/retail-centru-ground.jpg', 'Коммерческое помещение на первом этаже с витринными окнами, хорошим пешеходным трафиком и готовностью к адаптации под бизнес.', NOW(), NOW());

INSERT INTO property_requests (user_id, property_id, name, phone, email, message, preferred_contact_time, status, created_at) VALUES
(3, 1, 'Irina Popa', '+373 681 45 220', 'user@urbannest.test', 'Хотела бы посмотреть квартиру на этой неделе.', 'Будни после 16:00', 'new', NOW()),
(3, 4, 'Irina Popa', '+373 681 45 220', 'user@urbannest.test', 'Интересует офис для команды из 12 человек.', 'Утро, 10:00-12:00', 'contacted', NOW());

