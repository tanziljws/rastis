<?php

// Test script untuk REST API Laravel
// Jalankan dengan: php test_rest_api.php

$baseUrl = 'http://localhost:8000/api';

echo "=== Test REST API Laravel ===\n\n";

// Test 1: Login untuk dapat token
echo "1. Testing Login...\n";
$loginData = [
    'username' => 'admin',
    'password' => 'admin123'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

// Parse response to get token
$responseData = json_decode($response, true);
$token = $responseData['token'] ?? null;

if ($token) {
    echo "Token received: " . substr($token, 0, 20) . "...\n\n";
    
    // Test 2: Guest endpoints (tanpa token)
    echo "2. Testing Guest Endpoints (without token)...\n";
    
    // Get posts
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/posts?per_page=2');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "GET /posts - HTTP Code: $httpCode\n";
    echo "Response: " . substr($response, 0, 200) . "...\n\n";
    
    // Get profiles
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/profiles');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "GET /profiles - HTTP Code: $httpCode\n";
    echo "Response: " . substr($response, 0, 200) . "...\n\n";
    
    // Test 3: Admin endpoints (dengan token)
    echo "3. Testing Admin Endpoints (with token)...\n";
    
    // Get kategori list
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/kategori');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "GET /kategori - HTTP Code: $httpCode\n";
    echo "Response: " . substr($response, 0, 200) . "...\n\n";
    
    // Create new kategori
    $kategoriData = [
        'judul' => 'Kategori Test ' . date('Y-m-d H:i:s')
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/kategori');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($kategoriData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "POST /kategori - HTTP Code: $httpCode\n";
    echo "Response: $response\n\n";
    
    // Parse kategori response to get ID
    $kategoriResponse = json_decode($response, true);
    $kategoriId = $kategoriResponse['data']['id'] ?? null;
    
    if ($kategoriId) {
        // Create new post
        $postData = [
            'judul' => 'Post Test ' . date('Y-m-d H:i:s'),
            'kategori_id' => $kategoriId,
            'isi' => 'Ini adalah isi post test yang dibuat melalui API.',
            'status' => 'published'
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/posts');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "POST /posts - HTTP Code: $httpCode\n";
        echo "Response: " . substr($response, 0, 300) . "...\n\n";
        
        // Parse post response to get ID
        $postResponse = json_decode($response, true);
        $postId = $postResponse['data']['id'] ?? null;
        
        if ($postId) {
            // Create new galery
            $galeryData = [
                'post_id' => $postId,
                'position' => 1,
                'status' => 'active'
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl . '/galeries');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($galeryData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            echo "POST /galeries - HTTP Code: $httpCode\n";
            echo "Response: $response\n\n";
            
            // Parse galery response to get ID
            $galeryResponse = json_decode($response, true);
            $galeryId = $galeryResponse['data']['id'] ?? null;
            
            if ($galeryId) {
                // Create new foto
                $fotoData = [
                    'galery_id' => $galeryId,
                    'file' => 'test_foto_' . time() . '.jpg',
                    'judul' => 'Foto Test ' . date('Y-m-d H:i:s')
                ];
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $baseUrl . '/fotos');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fotoData));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $token,
                    'Content-Type: application/json',
                    'Accept: application/json'
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                echo "POST /fotos - HTTP Code: $httpCode\n";
                echo "Response: $response\n\n";
            }
        }
    }
    
    // Test 4: Admin dashboard
    echo "4. Testing Admin Dashboard...\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/dashboard');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "GET /admin/dashboard - HTTP Code: $httpCode\n";
    echo "Response: $response\n\n";
    
    // Test 5: Logout
    echo "5. Testing Logout...\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $baseUrl . '/logout');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "POST /logout - HTTP Code: $httpCode\n";
    echo "Response: $response\n\n";
    
} else {
    echo "Failed to get token from login response\n";
}

echo "=== Test Complete ===\n";
