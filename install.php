<?php
// Load credentials from config.env.php if available
if (file_exists(__DIR__ . '/config.env.php')) {
    require_once __DIR__ . '/config.env.php';
    $host = DB_HOST;
    $user = DB_USER;
    $pass = DB_PASS;
    $dbname = DB_NAME;
} else {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $dbname = 'maxiwheelchaircabs';
}

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");

    // Settings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `settings` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `setting_key` VARCHAR(100) UNIQUE NOT NULL,
        `setting_value` LONGTEXT,
        `setting_label` VARCHAR(255) DEFAULT NULL,
        `setting_group` VARCHAR(50) DEFAULT 'general'
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Services table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `services` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) UNIQUE NOT NULL,
        `short_description` TEXT,
        `description` LONGTEXT,
        `icon` VARCHAR(100) DEFAULT 'fas fa-taxi',
        `image` VARCHAR(255) DEFAULT '',
        `banner_image` VARCHAR(255) DEFAULT '',
        `meta_title` VARCHAR(255) DEFAULT '',
        `meta_description` TEXT,
        `meta_keywords` TEXT,
        `sort_order` INT DEFAULT 0,
        `status` TINYINT DEFAULT 1,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Testimonials table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `testimonials` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `location` VARCHAR(255) DEFAULT '',
        `review` TEXT,
        `rating` INT DEFAULT 5,
        `status` TINYINT DEFAULT 1,
        `sort_order` INT DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Inquiries table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `inquiries` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `name` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) DEFAULT '',
        `phone` VARCHAR(50) DEFAULT '',
        `service` VARCHAR(255) DEFAULT '',
        `pickup_location` TEXT,
        `dropoff_location` TEXT,
        `travel_date` VARCHAR(50) DEFAULT '',
        `passengers` VARCHAR(10) DEFAULT '',
        `message` TEXT,
        `status` ENUM('new','read','replied') DEFAULT 'new',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Admins table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(100) UNIQUE NOT NULL,
        `password` VARCHAR(255) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Pages table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `pages` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `title` VARCHAR(255) NOT NULL,
        `slug` VARCHAR(255) UNIQUE NOT NULL,
        `content` LONGTEXT,
        `meta_title` VARCHAR(255) DEFAULT '',
        `meta_description` TEXT,
        `meta_keywords` TEXT,
        `status` TINYINT DEFAULT 1,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // --- Seed Admin ---
    $adminCheck = $pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
    if ($adminCheck == 0) {
        $hashedPass = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)")->execute(['admin', $hashedPass]);
    }

    // --- Seed Settings ---
    $settings = [
        ['site_name', 'Maxi Wheelchair Cabs', 'Site Name', 'general'],
        ['phone_1', '(08) 1234 5678', 'Phone Number 1', 'general'],
        ['phone_2', '0412 345 678', 'Phone Number 2', 'general'],
        ['email', 'info@maxiwheelchaircabs.com.au', 'Email Address', 'general'],
        ['address', 'Perth, Western Australia', 'Address', 'general'],
        ['map_embed', '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d432626.2304726!2d115.61745!3d-31.9505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2a32966cdb47733d%3A0x304f0b535df55d0!2sPerth%20WA!5e0!3m2!1sen!2sau!4v1" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>', 'Map Embed Code', 'general'],
        ['facebook_url', 'https://facebook.com', 'Facebook URL', 'social'],
        ['instagram_url', 'https://instagram.com', 'Instagram URL', 'social'],
        ['whatsapp_number', '61412345678', 'WhatsApp Number', 'social'],
        ['twitter_url', 'https://twitter.com', 'Twitter/X URL', 'social'],
        ['linkedin_url', '', 'LinkedIn URL', 'social'],
        ['youtube_url', '', 'YouTube URL', 'social'],
        ['smtp_host', 'smtp.gmail.com', 'SMTP Host', 'mail'],
        ['smtp_port', '587', 'SMTP Port', 'mail'],
        ['smtp_username', '', 'SMTP Username', 'mail'],
        ['smtp_password', '', 'SMTP Password', 'mail'],
        ['smtp_from_email', '', 'From Email', 'mail'],
        ['smtp_from_name', 'Maxi Wheelchair Cabs', 'From Name', 'mail'],
        ['admin_email', '', 'Admin Notification Email', 'mail'],
        ['home_meta_title', 'Best Wheelchair Taxi & Maxi Cab Service in Perth | Maxi Wheelchair Cabs', 'Home Meta Title', 'seo'],
        ['home_meta_description', 'Perth\'s top-rated wheelchair accessible taxi service. Professional maxi cab, airport transfers, baby seat taxis & wedding transport. Book your reliable ride 24/7.', 'Home Meta Description', 'seo'],
        ['home_meta_keywords', 'wheelchair taxi Perth, maxi cab Perth, airport transfer Perth, baby seat taxi, wedding transport Perth, accessible taxi, disability taxi Perth, best taxi service Perth', 'Home Meta Keywords', 'seo'],
        ['hero_title', 'Professional <span>Wheelchair Taxi</span> & Maxi Cab Service', 'Hero Title', 'home'],
        ['hero_badge', 'Perth\'s Top-Rated Accessible Taxi', 'Hero Badge Text', 'home'],
        ['hero_description', 'Safe, comfortable and on-time transport you can always count on. Whether you need a wheelchair accessible vehicle, airport pickup, baby seat or wedding car — we have got you covered across Perth, WA.', 'Hero Description', 'home'],
        ['hero_image', 'images/hero-bg.jpg', 'Hero Background Image', 'home'],
        ['about_title', 'Perth\'s Most Trusted Accessible Taxi Service You Can Rely On', 'About Title', 'home'],
        ['about_subtitle', 'Welcome to Maxi Wheelchair Cabs', 'About Subtitle', 'home'],
        ['about_description', '<p>We are a dedicated team of professional drivers who truly understand what reliable, accessible transport means. Built from the ground up with one simple goal — to make every journey smooth, safe and stress-free for every passenger in Perth.</p><p>From wheelchair accessible vehicles designed for full comfort, to spacious maxi cabs perfect for families and groups, we go the extra mile so you never have to worry about getting where you need to be.</p>', 'About Description', 'home'],
        ['about_image', 'images/about-main.jpg', 'About Main Image', 'home'],
        ['about_image_small', 'images/about-small.jpg', 'About Small Image', 'home'],
        ['cta_title', 'Ready to Book Your Next Ride?', 'CTA Title', 'home'],
        ['cta_description', 'Call us now for fast, friendly and affordable taxi service across Perth.', 'CTA Description', 'home'],
        ['cta2_title', 'Need a Wheelchair Accessible Taxi Right Now?', 'CTA 2 Title', 'home'],
        ['cta2_description', 'Do not wait around. Our friendly team is standing by to get you where you need to go — safely, comfortably and on time.', 'CTA 2 Description', 'home'],
    ];

    $settingCheck = $pdo->query("SELECT COUNT(*) FROM settings")->fetchColumn();
    if ($settingCheck == 0) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, setting_label, setting_group) VALUES (?, ?, ?, ?)");
        foreach ($settings as $s) {
            $stmt->execute($s);
        }
    }

    // --- Seed Services ---
    $serviceCheck = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
    if ($serviceCheck == 0) {
        $services = [
            [
                'Wheelchair Taxi',
                'wheelchair-taxi',
                'Our specially fitted vehicles make every ride comfortable and dignified. Hydraulic ramps, secure restraints and spacious interiors mean you travel with total peace of mind.',
                '<h2>Perth\'s Best Wheelchair Accessible Taxi Service</h2>
<p>Getting around Perth should never be a challenge, no matter your mobility needs. At Maxi Wheelchair Cabs, we operate a fleet of purpose-built wheelchair accessible vehicles that are designed to make your journey as smooth and comfortable as possible.</p>

<h3>What Makes Our Wheelchair Taxis Different</h3>
<p>Every vehicle in our accessible fleet comes equipped with professional-grade hydraulic ramps or lifts, certified wheelchair restraint systems, and extra-wide doorways that accommodate all standard and powered wheelchairs. Our drivers are specifically trained in disability awareness and passenger assistance, so you always feel welcomed, respected and looked after.</p>

<h3>Who We Help</h3>
<p>Our wheelchair taxi service is perfect for:</p>
<ul>
<li>Daily appointments, medical visits and hospital trips</li>
<li>Social outings, shopping trips and family gatherings</li>
<li>Airport transfers with full wheelchair access</li>
<li>NDIS participants needing reliable accessible transport</li>
<li>Aged care residents heading to appointments or events</li>
</ul>

<h3>Comfortable, Dignified Travel Every Time</h3>
<p>We believe accessible transport should never feel like a compromise. That is why our vehicles are modern, clean and spacious — giving you plenty of room to travel in comfort. Whether you are heading to a medical appointment, visiting family or catching a flight, we treat every journey with the care it deserves.</p>

<h3>Easy Booking, Fair Pricing</h3>
<p>Booking a wheelchair taxi with us takes less than a minute. Call us directly or fill in our simple online form, and we will confirm your ride straight away. We charge standard metered or fixed fares — no call-out fees, no hidden costs, no surprises.</p>',
                'fas fa-wheelchair',
                'images/service-wheelchair.jpg',
                'Best Wheelchair Taxi Service Perth | Maxi Wheelchair Cabs',
                'Book Perth\'s top-rated wheelchair accessible taxi. Hydraulic ramps, trained drivers, NDIS friendly. Safe, comfortable rides 24/7. Call now!',
                'wheelchair taxi Perth, accessible taxi Perth, disability taxi, wheelchair transport, NDIS taxi Perth, handicap taxi service',
                1
            ],
            [
                'Airport Transfers',
                'airport-transfers',
                'Never miss a flight again. We track your departure and arrival times, handle your luggage and get you to or from Perth Airport without the rush or stress.',
                '<h2>Professional Perth Airport Transfer Service</h2>
<p>Start or end your trip the right way with a reliable, stress-free airport transfer. Maxi Wheelchair Cabs provides prompt, comfortable transport to and from Perth Airport — covering all terminals including T1, T2, T3 and T4.</p>

<h3>Why Choose Us for Airport Transfers</h3>
<p>We understand that airport travel comes with tight schedules and zero room for delays. That is exactly why our drivers monitor flight arrivals and departures in real time, arrive early for every pickup, and handle your luggage with care. You will never have to worry about missing a flight or waiting around after landing.</p>

<h3>What We Offer</h3>
<ul>
<li>Pickups and drop-offs at all Perth Airport terminals</li>
<li>Real-time flight tracking — we adjust for delays automatically</li>
<li>Early morning and late night transfers, any day of the year</li>
<li>Spacious vehicles for families, groups and extra luggage</li>
<li>Fixed fare options so you know the cost upfront</li>
<li>Meet and greet service at the arrivals terminal on request</li>
</ul>

<h3>Serving All Perth Suburbs</h3>
<p>No matter where you are in Perth — from Fremantle to Joondalup, Rockingham to Midland — we will get you to the airport on time. Our drivers know the fastest routes and plan ahead for traffic, construction and peak hour conditions.</p>

<h3>Book Your Airport Taxi Today</h3>
<p>Do not leave your airport travel to chance. Book with Perth\'s most dependable airport transfer service and enjoy a smooth, on-time ride every single time. Call us now or book online in under a minute.</p>',
                'fas fa-plane-departure',
                'images/service-airport.jpg',
                'Professional Airport Transfer Perth | Maxi Wheelchair Cabs',
                'Reliable Perth Airport taxi transfers to all terminals. Flight tracking, fixed fares, 24/7 service. Book your stress-free airport ride today!',
                'airport transfer Perth, Perth airport taxi, airport pickup Perth, airport drop off, flight transfer service, best airport cab Perth',
                2
            ],
            [
                'Taxi with Baby Seat',
                'taxi-with-baby-seat',
                'Travelling with little ones? Our taxis come fitted with government-approved baby capsules and child seats, securely installed so your children ride safely every time.',
                '<h2>Safe Baby Seat Taxi Service in Perth</h2>
<p>Travelling with babies and toddlers requires extra care, and at Maxi Wheelchair Cabs, we take child safety seriously. Our baby seat taxi service provides properly installed, government-approved child restraints so your little ones travel safely and you travel with peace of mind.</p>

<h3>Child Restraints You Can Trust</h3>
<p>Every child seat and baby capsule in our fleet meets Australian safety standards and is professionally installed before your trip. Our drivers are trained in correct fitting procedures and will double-check every restraint before setting off. Your children\'s safety is always our number one priority.</p>

<h3>What We Provide</h3>
<ul>
<li>Rear-facing baby capsules for newborns to 6 months</li>
<li>Forward-facing child seats for toddlers 6 months to 4 years</li>
<li>Booster seats for older children up to 7 years</li>
<li>Help with loading and unloading prams and strollers</li>
<li>Patient, family-friendly drivers who understand travelling with kids</li>
</ul>

<h3>Perfect for Families on the Move</h3>
<p>Whether you need a ride to the airport, a trip to the doctor, or transport to a family event, our baby seat taxis make it easy. No need to lug your own car seat around — just book, hop in and go. It really is that simple.</p>

<h3>Book a Baby Seat Taxi Now</h3>
<p>Give us a call or book online and let us know the ages of your children. We will make sure the right seats are ready and waiting when we arrive. Safe, convenient and hassle-free — that is what family travel should feel like.</p>',
                'fas fa-baby-carriage',
                'images/service-babyseat.jpg',
                'Baby Seat Taxi Perth | Safe Child Car Seat Taxi Service',
                'Book a baby seat taxi in Perth with government-approved child restraints. Safe, family-friendly rides for babies and toddlers. Call now!',
                'baby seat taxi Perth, child seat taxi, baby capsule taxi, family taxi Perth, toddler car seat cab, safe taxi for kids Perth',
                3
            ],
            [
                'Wedding Transport',
                'wedding-transport',
                'Make your special day run seamlessly. We offer punctual, elegant guest transfers at standard taxi rates, ensuring everyone arrives on time and in style.',
                '<h2>Affordable Wedding Transport Service in Perth</h2>
<p>Your wedding day should run like clockwork, and reliable guest transport is a big part of making that happen. Maxi Wheelchair Cabs offers professional, punctual wedding transfer services at standard taxi rates — helping your guests arrive relaxed, on time and ready to celebrate.</p>

<h3>Why Couples Choose Us</h3>
<p>We know how important the details are on your big day. That is why we assign dedicated drivers to your wedding booking, coordinate multiple vehicle pickups if needed, and stay in direct contact with your wedding planner or coordinator throughout the day. No missed pickups, no confusion, no stress.</p>

<h3>Our Wedding Transport Includes</h3>
<ul>
<li>Guest transfers from hotels to the ceremony venue</li>
<li>Ceremony to reception transport for the full wedding party</li>
<li>Late night transfers home for guests after the reception</li>
<li>Group transport in spacious maxi cabs seating up to 13</li>
<li>Wheelchair accessible wedding transport on request</li>
<li>Standard metered taxi rates — no inflated wedding pricing</li>
</ul>

<h3>Spacious Vehicles for Groups</h3>
<p>Our fleet includes 7-seater and 13-seater maxi cabs, perfect for bridesmaids, groomsmen and guest groups. Everyone travels together, arrives together and the celebration starts from the moment they step into the cab.</p>

<h3>Book Your Wedding Taxis</h3>
<p>Planning your big day? Get in touch early to lock in your preferred vehicles and times. We will work around your schedule and make sure every guest gets where they need to be — comfortably and on time.</p>',
                'fas fa-heart',
                'images/service-wedding.jpg',
                'Wedding Transport Perth | Affordable Guest Transfers',
                'Professional wedding transport in Perth at standard taxi rates. Maxi cabs for groups, punctual guest transfers. Book your wedding taxis today!',
                'wedding transport Perth, wedding taxi Perth, wedding guest transfer, bridal party transport, affordable wedding cars Perth, group wedding taxi',
                4
            ],
        ];

        $stmt = $pdo->prepare("INSERT INTO services (title, slug, short_description, description, icon, image, meta_title, meta_description, meta_keywords, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($services as $s) {
            $stmt->execute($s);
        }
    }

    // --- Seed Testimonials ---
    $testCheck = $pdo->query("SELECT COUNT(*) FROM testimonials")->fetchColumn();
    if ($testCheck == 0) {
        $testimonials = [
            ['Sarah Mitchell', 'Fremantle, WA', 'My mother uses a wheelchair and finding a reliable cab used to be a real struggle. These guys changed everything. The driver was patient, careful with the ramp and so respectful. We book with them every single week now.', 5, 1],
            ['James Walker', 'Rockingham, WA', 'Had an early morning flight at 5am and was worried about finding a cab that early. Booked the night before and the driver was there 10 minutes early. Professional, clean car, great price. Absolutely the best airport taxi in Perth.', 5, 2],
            ['Lisa Patel', 'Armadale, WA', 'Used their baby seat taxi for a trip with my two toddlers. The car seats were properly installed and the driver even helped me fold the pram. It felt like they genuinely cared about our safety. Highly recommend for families.', 5, 3],
        ];

        $stmt = $pdo->prepare("INSERT INTO testimonials (name, location, review, rating, sort_order) VALUES (?, ?, ?, ?, ?)");
        foreach ($testimonials as $t) {
            $stmt->execute($t);
        }
    }

    echo "<!DOCTYPE html><html><head><title>Installation Complete</title>
    <style>body{font-family:Poppins,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f8f9fa;margin:0;}
    .box{background:#fff;padding:50px;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.08);text-align:center;max-width:500px;}
    h1{color:#1a1a2e;margin-bottom:10px;}p{color:#6c757d;line-height:1.7;}
    .btn{display:inline-block;padding:14px 32px;background:#FFC107;color:#1a1a2e;border-radius:50px;text-decoration:none;font-weight:600;margin-top:20px;transition:all 0.3s;}
    .btn:hover{background:#e6ac00;transform:translateY(-2px);}
    .info{background:#f0f0f0;padding:15px;border-radius:8px;margin:20px 0;text-align:left;font-size:14px;}
    .info strong{color:#1a1a2e;}</style></head><body>
    <div class='box'>
    <h1>Installation Complete!</h1>
    <p>Database and tables created successfully with sample data.</p>
    <div class='info'>
    <strong>Admin Login:</strong><br>
    URL: <a href='admin/'>admin/</a><br>
    Username: <strong>admin</strong><br>
    Password: <strong>admin123</strong>
    </div>
    <p style='color:#e74c3c;font-size:13px;'>Please change your password after first login and delete this install.php file.</p>
    <a href='index.php' class='btn'>Visit Website</a>
    <a href='admin/' class='btn' style='margin-left:10px;background:#1a1a2e;color:#fff;'>Admin Panel</a>
    </div></body></html>";

} catch (PDOException $e) {
    die("Installation failed: " . $e->getMessage());
}
