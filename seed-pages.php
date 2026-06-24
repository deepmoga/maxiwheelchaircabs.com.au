<?php
require_once 'config.php';

$pageCheck = $pdo->query("SELECT COUNT(*) FROM pages")->fetchColumn();
if ($pageCheck == 0) {
    $pages = [
        [
            'Privacy Policy',
            'privacy-policy',
            '<h2>Privacy Policy</h2>
<p>At Maxi Wheelchair Cabs, we are committed to protecting the privacy of our customers. This Privacy Policy explains how we collect, use, disclose and safeguard your personal information.</p>

<h3>Information We Collect</h3>
<p>When you book a ride or contact us, we may collect your name, phone number, email address, pickup and drop-off locations, and any special requirements you share with us. This information is collected solely to provide you with our taxi services.</p>

<h3>How We Use Your Information</h3>
<ul>
<li>To process and confirm your taxi bookings</li>
<li>To communicate with you about your rides</li>
<li>To send booking confirmations and driver updates</li>
<li>To improve our services and customer experience</li>
<li>To respond to your inquiries and requests</li>
</ul>

<h3>Information Security</h3>
<p>We take reasonable steps to protect your personal information from misuse, loss, unauthorised access, modification or disclosure. Your data is stored securely and only accessed by authorised personnel.</p>

<h3>Third-Party Disclosure</h3>
<p>We do not sell, trade or transfer your personal information to third parties. We may share your information with our drivers solely for the purpose of completing your booked ride.</p>

<h3>Contact Us</h3>
<p>If you have any questions about this Privacy Policy, please contact us through our website or by phone.</p>',
            'Privacy Policy | Maxi Wheelchair Cabs Perth',
            'Read our privacy policy to understand how Maxi Wheelchair Cabs collects, uses and protects your personal information.',
            'privacy policy, data protection, personal information, taxi privacy policy Perth',
            1
        ],
        [
            'Terms & Conditions',
            'terms-conditions',
            '<h2>Terms & Conditions</h2>
<p>By using the services of Maxi Wheelchair Cabs, you agree to the following terms and conditions. Please read them carefully before making a booking.</p>

<h3>Booking and Cancellations</h3>
<p>All bookings are subject to vehicle availability. We recommend booking in advance, especially for airport transfers and wheelchair accessible vehicles. Cancellations should be made at least 2 hours before the scheduled pickup time. Late cancellations may incur a cancellation fee.</p>

<h3>Fares and Payment</h3>
<p>Fares are calculated using the standard taxi meter or a pre-agreed fixed price. We accept cash, credit cards and EFTPOS. Toll charges, airport fees and waiting time charges may apply where applicable.</p>

<h3>Passenger Responsibilities</h3>
<ul>
<li>Passengers must behave in a respectful manner at all times</li>
<li>Seatbelts must be worn by all passengers during the journey</li>
<li>Passengers are responsible for any damage caused to the vehicle</li>
<li>Children must be secured in appropriate child restraints</li>
</ul>

<h3>Our Responsibilities</h3>
<p>We will make every reasonable effort to arrive at the agreed pickup time. However, we cannot guarantee exact arrival times due to traffic conditions and other factors beyond our control. We take all reasonable care to ensure your safety and comfort during the journey.</p>

<h3>Liability</h3>
<p>Maxi Wheelchair Cabs will not be held liable for any delays, losses or damages arising from circumstances beyond our control, including but not limited to traffic conditions, road closures, weather events or mechanical issues.</p>

<h3>Changes to Terms</h3>
<p>We reserve the right to update these terms and conditions at any time. Continued use of our services constitutes acceptance of any changes.</p>',
            'Terms & Conditions | Maxi Wheelchair Cabs Perth',
            'Read the terms and conditions for using Maxi Wheelchair Cabs taxi services in Perth, WA.',
            'terms and conditions, taxi terms, booking conditions, taxi service terms Perth',
            1
        ],
    ];

    $stmt = $pdo->prepare("INSERT INTO pages (title, slug, content, meta_title, meta_description, meta_keywords, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($pages as $p) {
        $stmt->execute($p);
    }
    echo "Pages seeded successfully!";
} else {
    echo "Pages already exist (" . $pageCheck . " records).";
}

// Also seed the why choose us settings if not present
$stmt = $pdo->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, setting_label, setting_group) VALUES (?, ?, ?, ?)");
$whySettings = [
    ['whychoose_title', 'Honest Pricing, Experienced Drivers & Reliable Service Every Time', 'Why Choose Title', 'home'],
    ['whychoose_description', 'We are not just another taxi company. We are Perth locals who understand your travel needs and deliver a level of care that bigger operators simply cannot match.', 'Why Choose Description', 'home'],
    ['whychoose_image', 'images/why-choose.jpg', 'Why Choose Image', 'home'],
    ['hero_car_image', 'images/hero-car.png', 'Hero Car Image', 'home'],
    ['feature_1_title', 'No Hidden Charges', 'Feature 1 Title', 'home'],
    ['feature_1_desc', 'What we quote is what you pay. Transparent metered or fixed fares, always.', 'Feature 1 Desc', 'home'],
    ['feature_2_title', 'Professional Drivers', 'Feature 2 Title', 'home'],
    ['feature_2_desc', 'Trained, licensed and background-checked for your complete safety.', 'Feature 2 Desc', 'home'],
    ['feature_3_title', '24/7 Availability', 'Feature 3 Title', 'home'],
    ['feature_3_desc', 'Early morning flight or late night ride, we are always just a call away.', 'Feature 3 Desc', 'home'],
    ['feature_4_title', 'Driver SMS Alerts', 'Feature 4 Title', 'home'],
    ['feature_4_desc', 'Get a text when your driver is 10 minutes away. No guessing, no waiting.', 'Feature 4 Desc', 'home'],
    ['feature_5_title', 'Clean Modern Fleet', 'Feature 5 Title', 'home'],
    ['feature_5_desc', 'Well-maintained vehicles from sedans to 13-seater maxi cabs ready to go.', 'Feature 5 Desc', 'home'],
    ['feature_6_title', 'All Payment Methods', 'Feature 6 Title', 'home'],
    ['feature_6_desc', 'Cash, card, EFTPOS — pay however works best for you.', 'Feature 6 Desc', 'home'],
];
foreach ($whySettings as $ws) {
    $stmt->execute($ws);
}
echo "\nSettings seeded!";
